package sw3.protocol;

/**
 * The ProtocolMessage class encapsulates a complete message in
 * the Giraf protocol, from the initial message together with
 * any parameters it may require. The strength of this approach
 * over pure single-fragment communication is that we can run
 * communication exclusively with these objects, serializing and
 * deserializing them with Java's own methods.
 * A concern - somewhat of a combined upshot and downside - is that
 * protocol changes may require new revisions of this class. Done
 * properly, however, you could have separate classes for different
 * protocol versions.
 * @author Johannes Lindhart Borresen
 */
public class ProtocolMessage
{
    public String message;
    
    public Object[] parameters;
    
    /**
     * Creates a new ProtocolMessage with no parameters.
     * @param msg The message to convey. Remember these are case-sensitive.
     */
    public ProtocolMessage(String msg)
    {
        message = msg;
    }
    
    /**
     * Creates a new ProtocolMessage with zero or more parameters.
     * @param msg The message to convery. Remember these are case-sensitive.
     * @param args The parameters to push with the message. Remember that
     *              parameters are not named and thus they must come in a set
     *              order in current protocol versions.
     */
    public ProtocolMessage(String msg, Object ... args)
    {
        this(msg);
        
        parameters = args;
    }
}
