package sw3.server;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import sw3.lib.girafweb.Device;
import sw3.lib.girafweb.Profile;

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
}
