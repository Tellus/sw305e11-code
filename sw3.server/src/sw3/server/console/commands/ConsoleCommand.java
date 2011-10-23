/**
 * 
 */
package sw3.server.console.commands;

import java.io.IOException;

import javax.naming.OperationNotSupportedException;

import sw3.server.IServerProtocolCommand;
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
public abstract class ConsoleCommand implements IServerProtocolCommand
{
    /**
     * List of text commands that this command class responds to. I haven't
     * designer a proper response for conflicts yet.
     */
    protected String[] _Commands;
    
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
    public String[] getCommands(){return _Commands;}
    
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
    @Override
    public void execute(String params) throws OperationNotSupportedException
    {
        throw new OperationNotSupportedException("ConsoleCommand.execute() was called directly.");
    }
    
    @Override
    public abstract void setParent(sw3.server.ConnectionThread parent);
    
    @Override
    public abstract void setServer(ServerThread server);
    
    /**
     * Creates a new ConsoleCommand instance. Must be overridden and supply
     * meaningful data with setCommands (or addCommand/addCommands) and setName,
     * besides implementing execute.
     * @param newParent
     */
    public ConsoleCommand(ServerThread newParent){};
}
