package sw3.server;


/**
 * Main shizneh class. Starts up the server process and stuff.
 * @author Johannes
 */
public class Main
{	/**
	 * @param args
	 */
	public static void main(String[] args)
	{
		ServerThread server = new ServerThread();
		server.start();
	}
}
