package sw3.server;

import java.util.logging.Formatter;
import java.util.logging.LogRecord;

public class LoggingFormatter extends Formatter
{

    public LoggingFormatter()
    {
        // TODO Auto-generated constructor stub
    }

    @Override
    public String format(LogRecord rec)
    {
        // TODO Auto-generated method stub
        String output = "";
        
        output += "[" + rec.getLevel().getLocalizedName() + "]: ";
        
        output += rec.getMessage() + "\n";
        
        return output;
    }

}
