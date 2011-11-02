package sw3.androidupdater;

import java.io.IOException;
import java.net.UnknownHostException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import sw3.androidxmlclient.R;

import android.app.Activity;
import android.content.Context;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.os.Handler;
import android.os.SystemClock;
import android.view.View;
import android.widget.TextView;

public class AndroidXMLClientActivity extends Activity 
	
	{
	public TextView text;
	public String TextToDisplay;
	
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) 
    {
    	
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        
        mHandler.postDelayed(updateTextDoer, 1000);
    }
    
    public void connect_onClick(View view) throws UnknownHostException, IOException 
    {
    	text = (TextView)findViewById(R.id.TxtLog);
    	
    	//get mac address
    	String result = getMacAddress();
    	
    	//get md5 hash
    	TextToDisplay = md5(result);
    	
    	if (result != null) return;
    	
    	//Network n = new Network(this);
    	
    	//n.start();
    }
    
    //returns mac address of WiFi module. Works fine
    private String getMacAddress()
    {
    	WifiManager wifiMan = (WifiManager) this.getSystemService(Context.WIFI_SERVICE);
    	WifiInfo wifiInf = wifiMan.getConnectionInfo();
    	String macAddr = wifiInf.getMacAddress();

		return macAddr;
    }
    
    //Not done. 
    private final String md5(final String input)
    {
    	MessageDigest digest;
    	
    	try
    	{
    		int length;
    		byte[] byteArray;
    		
    		digest = MessageDigest.getInstance("MD5");
    		digest.reset();
    		digest.update(input.getBytes());
    		byteArray = digest.digest();
    		length = byteArray.length;
    		
    		//
    		StringBuilder sb = new StringBuilder(length << 1);
    		
    		for(int i = 0; i < length; i++)
    		{
    			sb.append(Character.forDigit((byteArray[i] & 0xf0) >> 4, 16));
    			sb.append(sb.append(Character.forDigit(byteArray[i] & 0x0f, 16)));
    		} 
    		return sb.toString();
    	}
    	catch(NoSuchAlgorithmException e) { e.printStackTrace();}
    	
    	return null;
    }
    
    //Uodates the text console field. Used for debugging. 
    private Runnable updateTextDoer = new Runnable()
    {
    	public void run()
    	{
    		long ms = SystemClock.uptimeMillis();
 
    		TextView v = (TextView)AndroidXMLClientActivity.this.findViewById(R.id.TxtLog);
    		v.setText(TextToDisplay);
 
    		mHandler.postAtTime(this, ms + 500);
    	}
    };
    private Handler mHandler = new Handler();
}