/**
 * 
 */
package sw3.server.console.commands;

import java.io.IOException;

import sw3.server.ServerThread;

/**
 * @author Johannes
 *  The ConsoleCommand class is a basis for all commands that can run within the
 *  sw3 Giraf server environment. By forming this structure, we can implement
 *  a very simple command request system for the server that determines if a
 *  command is offered by a ConsoleCommand derivative and, if so, calls it back.
 *  In the case of multiple commands, the ConsoleCommand may choose to further
 *  branch execution.
 */
public abstract class ConsoleCommand
{
    /**
     * List of text commands that this command class responds to. I haven't
     * designer a proper response for conflicts yet.
     */
    protected String[] _offeredCommands;
    
    /**
     * The primary identifier of the command.
     */
    protected String _name;
    
    /**
     * Retrieve the primary identifying name of the command object. Somewhat
     * similar to the toString() method. Name write-protection must be respected
     * to avoid certain doom.
     * @return
     */
    public String getName(){return _name;}
    
    /**
     * Retrieve all command aliases that this command class handles. Can only
     * be defined by the class itself and not outsiders.
     * @return
     */
    public String[] getOfferedCommands(){return _offeredCommands;}
    
    /**
     * Reference to the server thread that this command is bound to. Ugly,
     * but functional. It is mostly used when the command needs to affect the
     * calling environment... which is always, I guess.
     */
    public ServerThread parent;
    
    /**
     * This method is called whenever the server console has determined that
     * this class should handle a particular command. The complete array of
     * input is passed, including the command arg, so the command class can
     * determine possible alias commands.
     * @param params    List of parameters from the scanner. While the data type
     * is String, several parameters may be parsed as other data types.
     */
    public abstract void execute(String[] params);
    
    /**
     * Retrieves all sub classes of ConsoleCommand, either within the
     * sw3.lib.console.commands package (where all built-in commands reside) or
     * the entire classpath (slow!).
     * @param searchClassPath If true, the method will search the entire class
     * path, and not just a single package. 
     * @return An array of Classes that were found in the search path. May be
     * (but shouldn't be) empty.
     * @throws IOException 
     */
    public Class[] getSubclasses(Boolean searchClassPath) throws IOException
    {
        // TODO: This method is still not implemented.
        ClassLoader cl = Thread.currentThread().getContextClassLoader();
        
        // Set the package path.
        String path = "sw3/server/console/commands";
        
        // Retrieve all resources in this path.
        cl.getResources(path);
        
        return null;
    }
    
    public ConsoleCommand(ServerThread newParent)
    {
        parent = newParent;
    }
}
