package sw3.androidupdater;

import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.StreamCorruptedException;
import java.net.InetAddress;
import java.net.InetSocketAddress;
import java.net.Socket;
import java.net.UnknownHostException;
import java.sql.Timestamp;
import java.util.Calendar;
import java.util.Date;

public class Network extends Thread
{
	String currentContent;
	AndroidXMLClientActivity caller;
	Socket connection;
	ObjectOutputStream out;
	ObjectInputStream in;
	
	Network(AndroidXMLClientActivity dassCaller)
	{
		super();
		caller = dassCaller;
		caller.TextToDisplay = "Starting, lolz.";
	}
	/**
	 * @param args
	 */
	public void run()
	{	
		try {
			Connect();
			Initialize();
			Handshake();
			GetMessage();
			Output("Handshake sent");
			
			Version();
			GetMessage();
			Output("Version sent");
			
			SendTime();
			GetMessage();
			

			out.flush();
			//Everything should be fine at this point. lets flush and send commands: 
			//Fetch settings.xml from installed apks
			//Do SQL
			//Send SQL strings or files?
			
			Output("Handshake succesful. Requesting file.");
			out.writeUTF("SQL");
			out.writeUTF("FILE");
			out.flush();
			
			FileOutputStream file = new FileOutputStream("./datarecieved.xml");
			
			int tmp = -1;
			Output("Reading file...");
			
			while((tmp = in.read()) != 3) // 3 is EOT (End of Text) in ASCII and UTF-8.
			{
				Output("read " + tmp + "...");
				file.write(tmp);
			}
			file.close();;
			Output("Done reading!");
			
			out.writeUTF("GOODBYE");
			out.flush();
			out.close();
			in.close();
			connection.close();
			
			Output("We're done, motherfuckas!");
		} catch (StreamCorruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			Output(e.getMessage());
			e.printStackTrace();
		}
		
		catch (IOException e) 
		{
			// TODO Auto-generated catch block
			e.printStackTrace();	
		}
	}
	
	//Convenience method for printing output
	public void Output(String toWrite)
	{
		caller.TextToDisplay += "\n" + toWrite;
	}

	public void GetMessage()
	{

		try 
		{
			Output("Server answered: " + in.readUTF());
		} 
		catch (IOException e) 
		{
			Output("Reading string from server failed: ");
			e.printStackTrace();
			Bye();
		}
		
	}
	
	public void Connect() 
	{
		connection = new Socket();
		try 
		{
			connection.connect(new InetSocketAddress(InetAddress.getByName("81.19.212.68"), 2111));	
		} 
		catch (Exception e) 
		{
			
			Output("Attempt to connect failed: " + e.getMessage());
			e.printStackTrace();
			Bye();
		}
		Output("Connection succesful. Handshaking.");
	}
	
	public void Initialize()
	{
		try 
		{
			out = new ObjectOutputStream(connection.getOutputStream());
			out.flush();
			in = new ObjectInputStream(connection.getInputStream());

		} 
		catch (IOException e) 
		{
			Output("Attempt to start stream failed " + e.getMessage());
			e.printStackTrace();
			Bye();
		}
	}
	
	public void Handshake()
	{
		try 
		{
			out.writeUTF("HANDSHAKE");
		} 
		catch (IOException e) 
		{
			Output("Sending handshake failed." + e.getMessage());
			e.printStackTrace();
			Bye();
		}
	}
	
	public void Version()
	{
		try 
		{
			out.writeUTF("CockMungerZero");
		} 
		catch (IOException e) 
		{
			Output("Sending version failed: " + e.getMessage());
			e.printStackTrace();
			Bye();
		}
	}

	public void SendTime()
	{
		try 
		{
			Calendar calendar = Calendar.getInstance();
			Date date = calendar.getTime();
			Timestamp time = new Timestamp(date.getTime());
			out.writeObject(time);
		} 
		catch (IOException e) 
		{
			Output("Sending timestamp failed: " + e.getMessage());
			e.printStackTrace();
			Bye();
		}
		
	}

	public void Bye()
	{
		try {
			out.writeUTF("BYE");
			out.flush();
			in = null;
			out = null;
			
			connection = null;
			
		} catch (IOException e) {
			Output("Sending bye failed: " + e.getMessage());
			e.printStackTrace();
		}

	}
}

