package sw6.girafAppKit;

import android.app.ListActivity;
import android.content.Intent;
import android.os.Bundle;
import android.view.KeyEvent;

/**
 * This activity should be extended and used by activities in the 
 * GIRAF project. It provides access to the settings (by an Intent) through a secret keycombo,
 * (alternating vol up and vol down six times), ignoring certain other key presses, as well as
 * redefining the Intent used when opening settings, 
 * thus allowing to open application specific settings directly.
 * 
 * USAGE: Remember, the view MUST contain a ListView object with the id "@android:id/list" (or list if it's in code)
 * (also see http://developer.android.com/reference/android/app/ListActivity.html)
 * 
 * @author SW6C
 * 
 */
public abstract class GirafListActivity extends ListActivity {
    /**
     * Giraf*Activity Common functionality class.
     */
    ActivityFunction activityFunction = new ActivityFunction();

    /**
     * Internally called on Activity creation.
     * @param savedInstanceState Previously saved Instance of the Activity, if saved earlier. Else null.
     */
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        activityFunction.onCreate(this);
    }
    
    /**
     * The function is used for accessing the parental interface, which at the given time
     * is alternating pressing the Volume Up and Volume Down button, 6 times in total
     * (Up, Down, Up, Down, Up, Down).
     * If, during the sequence, a wrong sequence of buttons is pressed, the sequence 
     * must be pressed from the start again.
     * NOTE: The End-call, back and Home buttons can not be handled.
     * @param event The KeyEvent to be handled, for instance: KeyEvent.ACTION_DOWN.
     * @param keyCode An integer, used in the switch-case of this function.
     */
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event){
        boolean a = activityFunction.onKeyDown(keyCode, event);
        if (activityFunction.volCount == 6) {
            openParentInterface();
        }
        return a;
    }
    
    /**
     * Use this function to set another intent for the secret keycombo to start.
     * Use this with caution!
     * @param newIntent The new intent to be started by the secret keycombo.
     */
    public final void setParentIntent(Intent newIntent) {
        this.activityFunction.setParentIntent(newIntent);
    }

    /**
     * Helper function that will open the parent interface from {@link ActivityFunction#openParentInterface() 
     * openParentInterface()} in the {@link ActivityFunction ActivityFunction} class.
     */
    public final void openParentInterface() {
        activityFunction.volCount = 0;
        startActivity(new Intent(activityFunction.getParentIntent()));
    }
}