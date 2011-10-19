package sw3.server;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.net.ServerSocket;
import java.util.Properties;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.Scanner;

/**
 * The server class is a complete server solution,
 * that loads up according to a configuration file and
 * runs indefinitely until closed. The server can run
 * in daemon (unattended) or active (console-based) form.
 * @author Johannes Lindhart Borresen
 */
public class Server extends Thread
{
    public Properties config = new Properties();
    
    public String configPath = "./config.ini";
    
    public Logger log;

    public ServerSocket serverSocket;
    
    public Scanner input;
    
    public Server()
    {
        _createLogger();
        
        _createServerSocket();
        
        _createScanner();
        
        loadConfig();
        
        log.info("LOG: Server has started up. Be happy!");
    }
    
    @Override
    public void run()
    {
        boolean done = false;
        String cmd = "";
        while (!done)
        {
            if (input.hasNext())
            {
                cmd = input.next();
                log.info("Command: " + cmd);
                if (cmd.compareToIgnoreCase("quit") == 0) done = true;
            }
        }
    }
    
    private void _createScanner()
    {
        input = new Scanner(System.in);
    }

    private void _createServerSocket()
    {
        try
        {
            serverSocket = new ServerSocket(Integer.parseInt(config.getProperty("port")));
        }
        catch (NumberFormatException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }
    
    public void loadConfig()
    {
        if (!configExists())
        {
            log.info("Creating new config file.");
            createConfig();
        }
        
        try
        {
            config.load(new FileInputStream(getConfigFile()));
            log.info("Configuration file loaded successfully from path:\n" + getConfigFile().getAbsolutePath());
        }
        catch (FileNotFoundException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }
    
    /**
     * Retrieves a File object with a path leading to the
     * config file proper - calling this method ensures
     * existance of the file.
     * @return Handler to the config file.
     */
    public File getConfigFile()
    {
        if(!configExists()) createConfig();
        
        return new File(configPath);
    }
    
    /**
     * Creates a new configuration file with default values.
     * @return File handler to the new config file.
     */
    protected void createConfig()
    {
        File f = new File(configPath);
        
        if (f.exists())
        {
            f.delete();
        }
        
        try // We should be so solid that trying is simply a formality...
        {
            f.createNewFile();
        }
        catch (IOException e)
        {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        
        Properties p = new Properties();
        
        p.setProperty("port", "16489");
        p.setProperty("mode", "active");
        
        try
        {
            p.store(new FileOutputStream(f), "Default values ensue!");
            log.info("A new config file was written to the path '" + f.getAbsolutePath() + "'.");
        }
        catch (FileNotFoundException e)
        {
            log.severe("The config file could not be found for writing. This should NOT happen!!!");
            e.printStackTrace();
        }
        catch (IOException e)
        {
            log.severe("An error occurred while saving the config file");
            log.severe(e.getMessage());
        }
    }
    
    /**
     * Checks to see if a config file exists, and responds.
     * @return True if it exists, false otherwise.
     */
    protected boolean configExists()
    {
        File f = new File(configPath);
        
        if (f.exists())
        {
            log.finest("Config file already exists.");
            return true;
        }
        else
        {
            log.finest("Config file does not exist.");
            return false;
        }
    }
    
    protected void _createLogger()
    {
        log = Logger.getLogger("sw3server");
        log.setLevel(Level.ALL);
        log.
    }
}
