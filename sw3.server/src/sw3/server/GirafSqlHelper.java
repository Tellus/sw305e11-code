package sw3.server;


import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import sw3.lib.girafweb.Device;
import sw3.lib.girafweb.Profile;
import sw6.lib.girafplace.Application;
import sw6.lib.girafplace.State;
import sw6.lib.girafplace.UserProfile;

/**
 * The GirafSqlHelper subclass takes the base caretaking functionality of
 * SqlHelper and brings it into a context that specifically caters to our needs
 * within the context of Giraf and GirafWeb, particularly in regards to
 * expected database schemas and useful queries. 
 * @author Johannes Lindhart Borresen.
 */
public class GirafSqlHelper extends SqlHelper
{
    /// Table name "constants".
    public static String TABLE_PROFILES = "profiles";
    public static String TABLE_DEVICES = "devices";
    public static String TABLE_ABILITIES = "profileAbilities";
    public static String TABLE_ABILITY_DEFINITIONS = "abilityDefinitions";

    public GirafSqlHelper(String host, String user, String pass, String database)
            throws SQLException
    {
        super(host, user, pass, database);
    }

    public GirafSqlHelper(String connectionString, String user, String pass)
            throws SQLException
    {
        super(connectionString, user, pass);
    }

    public GirafSqlHelper(String host, int port, String user, String pass,
            String database) throws SQLException
    {
        super(host, port, user, pass, database);
    }
    
    /**
     * Creates a new Profile with data loaded from a specific device
     * ident and an active SqlHelper instance.
     * @param ident The ident to retrieve profile for. Currently, the ident is
     * a device's WiFI MAC address run through MD5.
     * @return  A new Profile instance with data from the database,
     * null otherwise.
     * @throws SQLException 
     */
    public Profile getProfile(String ident) throws SQLException
    {        
        // Since we're working with idents, we need a device to start from.
        Device dev = getDevice(ident);
        
        if (dev == null) return null; // Cop out if the ident matched nothing.
        
        String sql = "SELECT * FROM " + TABLE_PROFILES + " WHERE profileId=" + dev.getOwnerId();
        
        // Hopefully we only get one owner of an ident :)
        ResultSet result = connection.createStatement().executeQuery(sql);
        
        // If there are no hits, bail out.
        if (!result.first()) return null;
        else
        {
            // Create new profile and start fillin'.
            Profile prof = new Profile();
            
            return prof;
        }
    }
    
    public HashMap<String, Boolean> getProfileAbilities(String ident)
    {
        // TODO: Not finished.
        return new HashMap<String, Boolean>();
    }
    
    public HashMap<String, Boolean> getProfileAbilities(int profileId)
    {
        // TODO: Not finished.
        return new HashMap<String, Boolean>();
    }
    
    /**
     * Retrieves a device's data from the database based on its ident.
     * @param ident The ident (MD5'ed MAC address) of the device.
     * @return  A new Device if found, null otherwise.
     * @throws SQLException 
     */
    public Device getDevice(String ident) throws SQLException
    {
        String sql = "SELECT * FROM " + TABLE_DEVICES + " WHERE ident='" + ident + "'";
        
        Statement s = connection.createStatement();
        ResultSet result = s.executeQuery(sql);
        
        if (!result.first()) return null;
        else
        {
            return new Device(
                                result.getInt("deviceId"),
                                result.getInt("ownerId"),
                                result.getString("ident"),
                                result.getTimestamp("lastUpdated")
                                );
        }
    }
    
    /**
     * Checks whether a given device ident exists in the database.
     * @param ident
     * @return
     * @throws SQLException 
     */
    public Boolean hasIdent(String ident) throws SQLException
    {
        String sql = "SELECT COUNT(ident) AS hits FROM " + TABLE_DEVICES + " WHERE ident='" + ident + "'";
        Statement s = connection.createStatement();
        ResultSet found = s.executeQuery(sql);
        
        // If the result set is empty (first() returns false), then we have no hits.
        if (!found.first()) return false;
        else return true; // Somewhat haphazard, but idents are unique, so this should only guarantee *one* hit.
    }

    /**
     * Retrieves a list of all known devices.
     * @param maxHits   Limit of devices to retrieve.
     */
    public void getDevices(int maxHits)
    {
        // TODO Auto-generated method stub
        
    }
    
    /**
     * Retrieves a list of all known profiles.
     * @throws SQLException 
     */
    public List<Profile> getProfiles()
    {
        List<Profile> ret = new ArrayList<Profile>();
        
        try
        {
            ResultSet res = queryAsResultSet("SELECT * FROM " + TABLE_PROFILES);

            Profile p;
            
            while (res.next())
            {
                p = new Profile(res.getInt("profileId"));
                p.setAge(res.getInt("profileAge"));
                p.setName(res.getString("profileName"));
                ret.add(p);
            }
            
            return ret;            
        }
        catch (SQLException e)
        {
            // TODO: Figure out what would be a nice error handling here.
            e.printStackTrace();
            return ret; // Empty set.
        }
    }
    
    /**
     * Gets the applications from the database, that should be available for the
     * provided UserProfile
     * 
     * @param user
     *            The UserProfile that should be used to filter the applications
     * @return The applications from the database that the UserProfile is
     *         capable of using.
     * @throws SQLException
     *             If there is a problem with the SQL.
     * @throws ClassNotFoundException
     *             If the driver class is not found.
     */
    public List<Application> getApplications(UserProfile user)
            throws SQLException, ClassNotFoundException {
        String query = "SELECT * FROM applications WHERE ";

        if (!user.canDragAndDrop()) {
            query += " canDragAndDrop = 0 AND";
        }

        if (!user.canHear()) {
            query += " canHear = 0 AND";
        }

        if (!user.requiresSimpleVisualEffects()) {
            query += " requiresSimpleVisualEffects = 0 AND";
        }

        if (!user.canAnalogTime()) {
            query += " canAnalogTime = 0 AND";
        }

        if (!user.canDigitalTime()) {
            query += " canDigitalTime = 0 AND";
        }

        if (!user.canRead()) {
            query += " canRead = 0 AND";
        }

        if (!user.hasBadVision()) {
            query += " hasBadVision = 0 AND";
        }

        if (!user.requiresLargeButtons()) {
            query += " requiresLargeButtons = 0 AND";
        }

        if (!user.canSpeak()) {
            query += " canSpeak = 0 AND";
        }

        if (!user.canNumbers()) {
            query += " canNumbers = 0 AND";
        }

        if (!user.canUseKeyboard()) {
            query += " canUseKeyboard = 0 AND";
        }

        query += " state='LIVE'";

        // Omitted. We have persistent connections right now.
        // getConnection();
        Statement statement = connection.createStatement();

        List<Application> returnValue = new ArrayList<Application>();

        if (statement.execute(query)) {
            ResultSet result = statement.getResultSet();
            result.beforeFirst();

            while (result.next()) {
                final int id = result.getInt(1);
                final String name = result.getString(2);
                final String description = result.getString(3);
                final String package_ = result.getString(5);
                final int version = result.getInt(6);
                final String versionString = result.getString(7);
                final State state = State.LIVE;
                final boolean canRead = result.getBoolean(9);
                final boolean canDragAndDrop = result.getBoolean(10);
                final boolean canHear = result.getBoolean(11);
                final boolean requiresSimpleVisualEffects = result
                        .getBoolean(12);
                final boolean canAnalogTime = result.getBoolean(13);
                final boolean canDigitalTime = result.getBoolean(14);
                final boolean hasBadVision = result.getBoolean(15);
                final boolean requiresLargeButtons = result.getBoolean(16);
                final boolean canSpeak = result.getBoolean(17);
                final boolean canNumbers = result.getBoolean(18);
                final boolean canUseKeyboard = result.getBoolean(19);

                Application app = new Application(id, name, description,
                        package_, version, versionString, state);

                app.setCanRead(canRead);
                app.setCanDragAndDrop(canDragAndDrop);
                app.setCanHear(canHear);
                app.setRequiresSimpleVisualEffects(requiresSimpleVisualEffects);
                app.setCanAnalogTime(canAnalogTime);
                app.setCanDigitalTime(canDigitalTime);
                app.setHasBadVision(hasBadVision);
                app.setRequiresLargeButtons(requiresLargeButtons);
                app.setCanSpeak(canSpeak);
                app.setCanNumbers(canNumbers);
                app.setCanUseKeyboard(canUseKeyboard);

                returnValue.add(app);
            }
            return returnValue;
        } else
            return null;
    }
}
