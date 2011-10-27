package sw6.girafAppKit;

import android.app.Activity;
import android.content.ActivityNotFoundException;
import android.content.ComponentName;
import android.content.Intent;
import android.util.Log;
import android.view.KeyEvent;
import android.view.Window;
import android.view.WindowManager;

/**
 * Internal class for all Giraf*Activity classes to keep common functionality in one place, 
 * to as wide extent as possible. This class is not to be used by application developers,
 * unless they wish to implement other Android Activity types than already implemented.
 * 
 * @author SW6C
 *
 */
class ActivityFunction {
    private Intent parentIntent;
    int volCount = 0;
    private final String logTag= "GIRAF";

    /**
     * When creating an Activity, the activity is set to fullscreen.
     * @param a The Activity to fullscreen.
     */
    void onCreate(Activity a) {
        a.requestWindowFeature(Window.FEATURE_NO_TITLE);
        a.getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, 
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
    }
    
    /**
     * Used for intercepting key presses.
     * If the pressed key is volUp or volDown, volCount is increased (if the press is alternating).
     * In other cases, key presses that can be intercepted, are "handled" (that is, ignored).
     *
     * @param event The KeyEvent to be handled, for instance: KeyEvent.ACTION_DOWN.
     * @param keyCode An integer, used in the switch-case of this function.
     */
    public boolean onKeyDown(int keyCode, KeyEvent event){
    	if (event.getAction() == KeyEvent.ACTION_DOWN){
            switch(keyCode){
            	case KeyEvent.KEYCODE_BACK:
                case KeyEvent.KEYCODE_ENDCALL:
                case KeyEvent.KEYCODE_HOME:
                    return false;
                case KeyEvent.KEYCODE_VOLUME_DOWN:
                    if (volCount % 2 == 1 && volCount < 6) {
                        volCount++;
                    } else {
                        volCount = 0;
                    }
                    break;
                case KeyEvent.KEYCODE_VOLUME_UP:
                    if (volCount % 2 == 0 && volCount < 6) {
                        volCount++;
                    } else {
                        volCount = 0;
                    }
                    break;
                default:
                    break;
            }
            return true;
        }
        return false;
    }
    
    /**
     * Internal function to set the Intent, the key combo should start. 
     * Use this with caution!
     * @param newIntent The new intent to be started by the secret keycombo
     */
    public final void setParentIntent(Intent newIntent) {
        this.parentIntent = newIntent;
    }
    
    /**
     * Returns the parent Intent defined. If none defined, the default GIRAF administration panel intent will be returned.
     * @return The parent interface intent.
     */
    public final Intent getParentIntent() {
        try {
            if(parentIntent == null) {
            	Intent res = new Intent();
            	String mPackage = "sw6.admin";
            	String mClass = ".MainActivity";
            	res.setComponent(new ComponentName(mPackage,mPackage+mClass));
            	return res;
            }
            
            return parentIntent;
        } catch(ActivityNotFoundException e){
            Log.e(logTag, parentIntent + " is not allowed");
            return null;
        }
    }
}
