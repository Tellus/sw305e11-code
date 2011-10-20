package sw3.server;

import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.net.Socket;
import java.sql.Timestamp;

/**
 * The ConnectionThread class represents a single
 * inbound connection from a device and apart from
 * references to the parent ServerSocket (ServerThread.serverSocket)
 * it is self-contained for now.
 * @author Johannes Lindhart Borresen
 *
 */
public class ConnectionThread extends Thread
{
    /**
     * Total count of active threads in our server.
     */
    private static int _threadCount = 0; 
    
    /**
     * Get the count of active threads on our server.
     * @return
     */
    public static int getThreadCount()
    {
        return _threadCount;
    }
    
    /**
     * Internal id of the thread.
     */
    private int _threadId;
    
    /**
     * Gets the thread's id as it is identified by the ServerThread.
     * @return
     */
    public int getThreadId()
    {
        return _threadId;
    }
    
    /**
     * Sets the thread id. Should only be settable by the
     * constructor and the thread itself to avoid possible
     * conflicts.
     * @param newId
     */
    protected void setThreadId(int newId)
    {
        _threadId = newId;
    }
    
    /**
     * Parent thread of this connection. This parent contains
     * information regarding the primary socket as well as
     * core objects that are to be used by all clients (say, 
     * for example, active database connections).
     */
    public ServerThread Parent;
    
    /**
     * The socket that this thread is working with. This should
     * represent a single connection to a client device.
     */
    public Socket clientSocket;
    
    /**
     * Identifier for the incoming device. By protocol standard,
     * this is currently their WiFi MAC address run through an
     * MD5 hash.
     */
    public String ident;
    
    /**
     * Input stream for the socket.
     */
    public ObjectInputStream input;
    
    /**
     * Output stream for the socket.
     */
    public ObjectOutputStream output;
    
    public CommandOpEnum loopSignal;
    
    /**
     * Creates a new connection thread, bound to a (newly
     * created) socket from an inbound client connection.
     * @param conn  The newly-created socket, from serverSocket.accept()
     * @param parent    The parent that spawned this thread. Used for inter-thread communication.
     */
    public ConnectionThread(Socket conn, ServerThread parent)
    {
        clientSocket = conn;
        Parent = parent;
        setThreadId(++_threadCount);
        
        loopSignal = CommandOpEnum.NOOP;
        
        createStreams();
        
        log("New connection from " + clientSocket.getRemoteSocketAddress());
    }
    
    /**
     * Creates the input and output streams required for
     * communication across the network socket.
     */
    protected void createStreams()
    {
        try
        {
            // For some reason, the order and method of initiation is crucial...
            output = new ObjectOutputStream(clientSocket.getOutputStream());
            output.flush();
            input = new ObjectInputStream(clientSocket.getInputStream());
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        finally
        {
            log("Successfully created network streams.");
        }
    }
    
    @Override
    public void run()
    {
        // Set this to true once we've done the initial handshake.
        boolean hasHandshake = false;
        String msg = "";
        while (loopSignal != CommandOpEnum.TERM)
        {
            try
            {
                msg = input.readUTF();
            }
            catch (IOException inputE)
            {
                // e1.printStackTrace();
                log("Failed to read from the stream:\n" + inputE.getMessage());
                panic();
                return;
            }
            
            if (hasHandshake)
            {
                if (msg.compareToIgnoreCase("BYE") == 0)
                {
                    loopSignal = CommandOpEnum.TERM;
                }
            }
            else
            {
                if (isMatch(msg, "HELLO"))
                {
                    try
                    {
                        doHandshake();
                    }
                    catch (IOException e)
                    {
                        // TODO Auto-generated catch block
                        // e.printStackTrace();
                        log("Failed to handshake, panic!");
                        panic();
                    }
                    finally
                    {
                        hasHandshake = true;
                    }
                }
                else if (isMatch(msg, "TIMESTAMP"))
                {
                    doTimestamp();
                }
                else if (isMatch(msg, "IDENT"))
                {
                    doIdent();
                }
                else if (isMatch(msg, "BYE"))
                {
                    loopSignal = CommandOpEnum.TERM;
                }
            }
        }
        close();
    }
    
    protected void doIdent()
    {
        try
        {
            String ident = input.readUTF();
            log("Client reports ident " + ident + "... let's fuck with them.");
            writeString("ERROR");
            writeString("UNKNOWN");
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    protected void doTimestamp()
    {
        try
        {
            // For now, we just ignore the incoming timestamp.
            Timestamp clientTs = (Timestamp)input.readObject();
            log("Client sends timestamp: " + clientTs.toString());
            writeString("OK");
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        catch (ClassNotFoundException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    
    
    /**
     * Shorthand for (one.compareToIgnoreCase(two) == 0) OR
     *               (one.compareTo(two == 0) depending on ignoreCase.
     * @param one   First string in comparison.
     * @param two   String you want the first string compared to.
     * @param ignoreCase    Whether to ignore case.
     * @return
     */
    protected final boolean isMatch(String one, String two, boolean ignoreCase)
    {
        if (ignoreCase)
        {
            return (one.compareToIgnoreCase(two) == 0);
        }
        else return isMatch(one, two);
    }
    
    /**
     * Shorthand for (one.compareTo(two) == 0);
     * @param one   First string in comparison.
     * @param two   String you want the first string compared to.
     * @return
     */
    protected final boolean isMatch(String one, String two)
    {
        if (one.compareTo(two) == 0) return true;
        else return false;
    }
    
    /**
     * Performs the motions for a proper handshake in v2.
     * @throws IOException 
     */
    protected void doHandshake() throws IOException
    {
        String version = "UNKNOWN";
        try
        {
            // The protocol specifies that all handshakes must be
            // followed by a version string.
            version = input.readUTF();
            log("Client reports version " + version);
            
            // Afterwards, send an OK for continued communication.
            writeString("OK");
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            // e.printStackTrace();
            throw new IOException("Failed to finish handshake.", e);
        }
        
        return;
    }
    
    /**
     * Placeholder for proper socket error handling.
     * Closes the socket and this thread in a relatively elegant fashion.
     */
    public void panic()
    {
        try
        {
            // Send a termination message to the client, hoping they get it.
            writeString("BYE");
            close();
        }
        catch (Exception e)
        {
            // TODO: handle exception
        }
    }
    
    /**
     * Writes a string to the output stream as UTF and flushes.
     * @param msg   The message to send.
     * @throws IOException 
     */
    public void writeString(String msg) throws IOException
    {
        output.writeUTF(msg);
        output.flush();
    }
    
    /**
     * Closes the connection as gracefully as is almost possible.
     */
    public void close()
    {
        try
        {
            // Close the socket.
            clientSocket.close();
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            // e.printStackTrace();
            log("Failed to gracefully close socket... whatever");
        }
    }
    
    /**
     * Logs a FINEST message to the parent's Logger.
     * @param msg The message to convey, will automatically be prepended
     * with the thread's id.
     */
    protected void log(String msg)
    {
        Parent.logFinest("(" + getThreadId() + ") " + msg);
    }
}
