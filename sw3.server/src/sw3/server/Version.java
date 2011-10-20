package sw3.server;

/**
 * The version class is a simple data container
 * to simply convey version number around.
 * @author Johannes Lindhart Borresen
 */
public class Version
{
    /**
     * The major version number. 
     */
    public int Major;
    
    /**
     * The minor version number.
     */
    public int Minor;
    
    /**
     * The build number.
     */
    public int Build;
    
    public Version(int maj, int min, int b)
    {
        Major = maj;
        Minor = min;
        Build = b;
    }
    
    /**
     * Creates a new Version object.
     * @param ver A version as a string in the form "<Major>.<Minor>.<Build>"
     */
    public Version(String ver)
    {
        String[] vers = ver.split(".");
        try
        {
            Major = Integer.parseInt(vers[0]);
            Minor = Integer.parseInt(vers[1]);
            Build = Integer.parseInt(vers[2]);
        }
        catch (Exception e)
        {
            // TODO: handle exception
            throw new NumberFormatException("The input string '" + ver + "' was in an invalid format.");
        }
    }
    
    @Override
    public String toString()
    {
        return String.format("%1$.%2$.%3$", Major, Minor, Build);
    }
}
