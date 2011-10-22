package sw6.lib.internal.debug;

import java.util.ArrayList;

import android.util.Log;

public class DebugHelpers {

	public static boolean[] getBooleanArray(ArrayList<Boolean> arrayList) {
		boolean[] booleanArray = new boolean[arrayList.size()];
		for(int i = 0; i < arrayList.size(); i++) {
			booleanArray[i] = arrayList.get(i).booleanValue();
		}
		return booleanArray;
	}

	public static void logIssue(int testNumber, String appName, String msg) {
		Log.e(appName + " #" + testNumber, msg);
	}
	
	public static void logPass(int testNumber, String appName) {
		Log.i(appName + " #" + testNumber, "Test passed.");
	}
}
