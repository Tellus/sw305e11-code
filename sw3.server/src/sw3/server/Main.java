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
		Server server = new Server();
		server.start();
	}
}
