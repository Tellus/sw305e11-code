package sw3.server;

/**
 * This is a helper thread for the Server class that
 * simply listens for incoming connections, assigns them
 * a new socket instance and sets them off on their good
 * tides, while logging the occurrences as necessary.
 * @author Johannes Lindhart Borresen
 */
public class SocketThread extends Thread
{
    public ServerThread Parent;

    protected boolean isDone = false;
    
    public SocketThread(ServerThread parent)
    {
        Parent = parent;
    }

    /**
     * Signals the thread to shut down after its next iteration.
     * Note that this will *not* shut down the existing connections.
     * They can be terminated one by one within the Server.
     */
    public void close()
    {
        isDone = true;
    }
    
    @Override
    public void run()
    {
        while (!isDone)
        {
            
        }
    };

}
