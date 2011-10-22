package sw6.lib;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.Serializable;

import sw6.lib.exceptions.SerializationException;
import sw6.lib.exceptions.SettingNotFoundException;
import sw6.lib.internal.PrivateDefinitions;
import sw6.lib.user.Profile;
import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Context;
import android.database.ContentObserver;
import android.database.Cursor;
import android.net.Uri;

/**
 * A library to include in any app to get access to settings stored in the administration module.
 * The library provides getters and setters for basic primitives (boolean and integers), as well
 * as String objects and your own objects of any type.
 * @author sw6b | room 3.1.46 | <a href="mailto:sw6b@lcdev.dk">sw6b\@lcdev.dk</a>
 */
public final class Settings {
	
	/**
	 * These identify the attributes sent in a bundle when
	 * the custom activity is started for an object.
	 */
	public static final String OBJECT_ATTRIBUTE_VARNAME			= "varName";
	public static final String OBJECT_ATTRIBUTE_DATATYPE		= "type";
	public static final String OBJECT_ATTRIBUTE_DESCRIPTION		= "desc";
	public static final String OBJECT_ATTRIBUTE_REALNAME		= "realName";
	public static final String OBJECT_ATTRIBUTE_PACKAGENAME		= "packageName";
	
	private Settings() { }
	
	/**
	 * Returns information about the user profile encapsulated in a <code>sw6.lib.user.Profile</code> object.
	 * The object utilizes lazy loading such that the newest user profile property are always returned
	 * when requested.
	 * @param context The context of your application.
	 * @return The user profile encapsulated in a <code>sw6.lib.user.Profile</code> object.
	 */
	public static Profile getUserProfile(Context context) {
		return new Profile(context);
	}
	
	/**
	 * Returns the requested variable as a boolean primitive. The variable is requested from the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as a boolean primitive. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static boolean getBoolean(Context context, String varName) throws SettingNotFoundException {
		return getBoolean(context, context.getPackageName(), varName);
	}
	
	/**
	 * Returns the requested variable as a boolean primitive. The variable is requested from the administration database.
	 * The method can also be used to get the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as a boolean primitive. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static boolean getBoolean(Context context, String appName, String varName) throws SettingNotFoundException {
		Cursor settingCursor = getSetting(context, appName, varName, PrivateDefinitions.BOOLEAN);
		boolean value = (settingCursor.getInt(0) == 1);
		settingCursor.close();
		return value;
	}
	
	/**
	 * Returns the requested variable as an enum element. The variable is requested from the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as an enum element. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static int getEnum(Context context, String varName) throws SettingNotFoundException {
		return getEnum(context, context.getPackageName(), varName);
	}
	
	/**
	 * Returns the requested variable as an enum element. The variable is requested from the administration database.
	 * The method can also be used to get the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as an enum element. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static int getEnum(Context context, String appName, String varName) throws SettingNotFoundException {
		Cursor settingCursor = getSetting(context, appName, varName, PrivateDefinitions.ENUM);
		int value = settingCursor.getInt(0);
		settingCursor.close();
		return value;
	}
	
	/**
	 * Returns the requested variable as an integer primitive. The variable is requested from the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as an integer primitive. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static int getInteger(Context context, String varName) throws SettingNotFoundException {
		return getInteger(context, context.getPackageName(), varName);
	}
	
	/**
	 * Returns the requested variable as an integer primitive. The variable is requested from the administration database.
	 * The method can also be used to get the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as an integer primitive. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static int getInteger(Context context, String appName, String varName) throws SettingNotFoundException {
		Cursor settingCursor = getSetting(context, appName, varName, PrivateDefinitions.INTEGER);
		int value = settingCursor.getInt(0);
		settingCursor.close();
		return value;
	}
	
	/**
	 * Returns the requested variable as an double primitive. The variable is requested from the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as an double primitive. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static double getDouble(Context context, String varName) throws SettingNotFoundException {
		return getDouble(context, context.getPackageName(), varName);
	}
	
	/**
	 * Returns the requested variable as an double primitive. The variable is requested from the administration database.
	 * The method can also be used to get the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as an double primitive. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static double getDouble(Context context, String appName, String varName) throws SettingNotFoundException {
		Cursor settingCursor = getSetting(context, appName, varName, PrivateDefinitions.DOUBLE);
		double value = settingCursor.getDouble(0);
		settingCursor.close();
		return value;
	}
	
	/**
	 * Returns the requested variable as a String object. The variable is requested from the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as a String object. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static String getString(Context context, String varName) throws SettingNotFoundException {
		return getString(context, context.getPackageName(), varName);
	}
	
	/**
	 * Returns the requested variable as a String object. The variable is requested from the administration database.
	 * The method can also be used to get the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @return The requested variable as a String object. The variable is requested from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static String getString(Context context, String appName, String varName) throws SettingNotFoundException {
		Cursor settingCursor = getSetting(context, appName, varName, PrivateDefinitions.STRING);
		String value = settingCursor.getString(0);
		settingCursor.close();
		return value;
	}
	
	/**
	 * Returns the requested variable as Cursor object. The method utilizes sw6.admin.SettingsProvider to query the variable from the administration database.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @param dataType The datatype of the variable to request. Defined in sw6.lib.internal.Definitions.
	 * @return Returns the requested variable as Cursor object. The method utilizes the sw6.admin.SettingsProvider to query the variable from the administration database.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	private static Cursor getSetting(Context context, String appName, String varName, String dataType) throws SettingNotFoundException {
		Uri settingRequest 		= Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, dataType + "/" + appName + "/" +  varName);
		Cursor settingCursor 	= context.getContentResolver().query(settingRequest, null, null, null, null);
		
		if(settingCursor == null || settingCursor.moveToFirst() == false) {
			throw new SettingNotFoundException(appName, varName, dataType);
		}
		
		return settingCursor;
	}
    
    /**
     * Provides the same functionality as: {@link #getObject(Context, String, Class)} and is only available for readability concerns.
	 * @param <T> The type of the stdobject to request.
	 * @param context The context of your application.
	 * @param varName The name of the variable to request.
	 * @param someClass The Class object of the stdobject to request.
	 * @return The requested stdobject as an instance of the Class object provided.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
     */
	public static <T extends Serializable> T getStdObject(Context context, String varName, Class<T> someClass) throws SettingNotFoundException {
    	return getObject(context, varName, someClass);
    }
    
    /**
     * Provides the same functionality as: {@link #getObject(Context, String, String, Class)} and is only available for readability concerns.
	 * @param <T> The type of the stdobject to request.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @param someClass The Class object of the stdobject to request.
	 * @return The requested stdobject as an instance of the Class object provided.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
     */
	public static <T extends Serializable> T getStdObject(Context context, String appName, String varName, Class<T> someClass) throws SettingNotFoundException {
    	return getObject(context, appName, varName, someClass);
    }
	
	/**
	 * Returns the requested variable as an instance of the class provided. The variable is requested from the administration database.
	 * @param <T> The type of the object to request.
	 * @param context The context of your application.
	 * @param varName The name of the variable to request.
	 * @param someClass The Class object of the object to request.
	 * @return The requested object as an instance of the Class object provided.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static <T extends Serializable> T getObject(Context context, String varName, Class<T> someClass) throws SettingNotFoundException {
		return getObject(context, context.getPackageName(), varName, someClass);
	}
	
	/**
	 * Returns the requested variable as an instance of the class provided. The variable is requested from the administration database.
	 * The method can also be used to get the value of a variable shared by multiple applications.
	 * @param <T> The type of the object to request.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to request.
	 * @param someClass The Class object of the object to request.
	 * @return The requested object as an instance of the Class object provided.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
    public static <T extends Serializable> T getObject(Context context, String appName, String varName, Class<T> someClass) throws SettingNotFoundException {

		Uri settingRequest 		= Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.OBJECT + "/" + appName + "/" +  varName + "/" + someClass.getCanonicalName());
		Cursor settingCursor 	= context.getContentResolver().query(settingRequest, null, null, null, null);
		
		if(settingCursor == null || settingCursor.moveToFirst() == false) {
			throw new SettingNotFoundException(appName, varName, someClass.getCanonicalName());
		}
		
		byte[] byteArrayRestoredObject = settingCursor.getBlob(0);
		settingCursor.close();
		
		if (byteArrayRestoredObject.length == 0) {
			return null;
		}
    	
    	ByteArrayInputStream byteArrayInputStream 	= new ByteArrayInputStream(byteArrayRestoredObject);
    	ObjectInputStream objectInputStream 		= null;
    	Object object								= null;
    	try {
    		objectInputStream 	= new ObjectInputStream(byteArrayInputStream);
    		object 				= objectInputStream.readObject();
    		objectInputStream.close();
    	} catch (Exception e) {
    		e.printStackTrace();
			throw new SerializationException("The variable with the name: \"" + varName + "\" for the application: \"" + appName + 
					 "\" could not be loaded with the serialized value of the object type: \"" + 
					 someClass.getClass().getCanonicalName() + "\".");
    	}

    	return someClass.cast(object);
    }
    
    
    /**
     * Updates the value of a boolean variable. The value of the variable is updated in the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to update.
     * @param varValue The new boolean value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown.
     */
    public static void setBoolean(Context context, String varName, boolean varValue) throws SettingNotFoundException {
    	setBoolean(context, context.getPackageName(), varName, varValue);
    }
    
    /**
     * Updates the value of a boolean variable. The value of the variable is updated in the administration database.
     * The method can also be used to set the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new boolean value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown.
     */
    public static void setBoolean(Context context, String appName, String varName, boolean varValue) throws SettingNotFoundException {
    	ContentValues contentValues = new ContentValues();
    	contentValues.put(PrivateDefinitions.VAR_VALUE, varValue);
    	setSetting(context, appName, varName, PrivateDefinitions.BOOLEAN, contentValues);
    }
    
    /**
     * Updates the value of an enum variable. The value of the variable is updated in the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to update.
     * @param varValue The new enum value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setEnum(Context context, String varName, int varValue) throws SettingNotFoundException {
    	setEnum(context, context.getPackageName(), varName, varValue);
    }
    
    /**
     * Updates the value of an enum variable. The value of the variable is updated in the administration database.
     * The method can also be used to set the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new enum value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setEnum(Context context, String appName, String varName, int varValue) throws SettingNotFoundException {
    	ContentValues contentValues = new ContentValues();
    	contentValues.put(PrivateDefinitions.VAR_VALUE, varValue);
    	setSetting(context, appName, varName, PrivateDefinitions.ENUM, contentValues);
    }
    
    /**
     * Updates the value of an integer variable. The value of the variable is updated in the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to update.
     * @param varValue The new integer value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setInteger(Context context, String varName, int varValue) throws SettingNotFoundException {
    	setInteger(context, context.getPackageName(), varName, varValue);
    }
    
    /**
     * Updates the value of an integer variable. The value of the variable is updated in the administration database.
     * The method can also be used to set the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new integer value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setInteger(Context context, String appName, String varName, int varValue) throws SettingNotFoundException {
    	ContentValues contentValues = new ContentValues();
    	contentValues.put(PrivateDefinitions.VAR_VALUE, varValue);
    	setSetting(context, appName, varName, PrivateDefinitions.INTEGER, contentValues);
    }
    
    /**
     * Updates the value of an double variable. The value of the variable is updated in the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to update.
     * @param varValue The new double value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setDouble(Context context, String varName, double varValue) throws SettingNotFoundException {
    	setDouble(context, context.getPackageName(), varName, varValue);
    }
    
    /**
     * Updates the value of an double variable. The value of the variable is updated in the administration database.
     * The method can also be used to set the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new double value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setDouble(Context context, String appName, String varName, double varValue) throws SettingNotFoundException {
    	ContentValues contentValues = new ContentValues();
    	contentValues.put(PrivateDefinitions.VAR_VALUE, varValue);
    	setSetting(context, appName, varName, PrivateDefinitions.DOUBLE, contentValues);
    }
    
    /**
     * Updates the value of a String variable. The value of the variable is updated in the administration database.
	 * @param context The context of your application.
	 * @param varName The name of the variable to update.
     * @param varValue The new String value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setString(Context context, String varName, String varValue) throws SettingNotFoundException {
    	setString(context, context.getPackageName(), varName, varValue);
    }
    
    /**
     * Updates the value of a String variable. The value of the variable is updated in the administration database.
     * The method can also be used to set the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new String value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setString(Context context, String appName, String varName, String varValue) throws SettingNotFoundException {
    	ContentValues contentValues = new ContentValues();
    	contentValues.put(PrivateDefinitions.VAR_VALUE, varValue);
    	setSetting(context, appName, varName, PrivateDefinitions.STRING, contentValues);
    }
    
    /**
     * Updates the value of the specified variable. The method utilizes sw6.admin.SettingsProvider to update the variable in the administration database.
	 * @param context The context of your application.
	 * @param appName The name of your application.
	 * @param varName The name of the variable to update.
	 * @param dataType The datatype of the variable to update. Defined in sw6.lib.internal.Definitions.
     * @param contentValues The new value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    private static void setSetting(Context context, String appName, String varName, String dataType, ContentValues contentValues) throws SettingNotFoundException {
    	Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, dataType + "/" + appName + "/" +  varName);
		int affectedRows = context.getContentResolver().update(settingRequest, contentValues, null, null);
		if(affectedRows == 0) {
			throw new SettingNotFoundException(appName, varName, dataType);
		}
    }
    
    /**
     * Provides the same functionality as: {@link #setObject(Context, String, Serializable)} and is only available for readability concerns.
	 * @param context The context of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new Object value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setStdObject(Context context, String varName, Serializable varValue) throws SettingNotFoundException {
    	int affectedRows = setObject(context, context.getPackageName(), varName, varValue);
    	if(affectedRows == 0) {
			throw new SettingNotFoundException(context.getPackageName(), varName, PrivateDefinitions.STDOBJECT);
		}
    }
    
    /**
     * Provides the same functionality as: {@link #setObject(Context, String, String, Serializable)} and is only available for readability concerns.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new Object value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setStdObject(Context context, String appName, String varName, Serializable varValue) throws SettingNotFoundException {
    	int affectedRows = setObject(context, appName, varName, varValue);
    	if(affectedRows == 0) {
			throw new SettingNotFoundException(appName, varName, PrivateDefinitions.STDOBJECT);
		}
    }
    
    /**
     * Updates the specified variable with a new object. The variable is updated in the administration database.
	 * @param context The context of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new Object value.
     * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database
     * the setting cannot be updated, and this exception is thrown
     */
    public static void setObject(Context context, String varName, Serializable varValue) throws SettingNotFoundException {
    	int affectedRows = setObject(context, context.getPackageName(), varName, varValue);
    	if(affectedRows == 0) {
			throw new SettingNotFoundException(context.getPackageName(), varName, PrivateDefinitions.OBJECT);
		}
    }
    
    /**
     * Updates the specified variable with a new object. The variable is updated in the administration database.
     * The method can also be used to set the value of a variable shared by multiple applications.
	 * @param context The context of your application.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to update.
     * @param varValue The new Object value.
     * @return The number of rows affected by the update
     */
	private static int setObject(Context context, String appName, String varName, Serializable varValue) {
    	ContentValues contentValues = new ContentValues();
    	
		ByteArrayOutputStream baos 	= new ByteArrayOutputStream();
	    ObjectOutputStream oos 		= null;
		
	    try {
			oos = new ObjectOutputStream(baos);
			oos.writeObject(varValue);
			
			byte[] byteArrayOutputSerialized = baos.toByteArray();
			oos.close();
	    	
	    	contentValues.put(PrivateDefinitions.VAR_VALUE, byteArrayOutputSerialized);
	    	Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.OBJECT + "/" + appName + "/" +  varName + "/" + varValue.getClass().getCanonicalName());
			return context.getContentResolver().update(settingRequest, contentValues, null, null);
			
		} catch (IOException e) {
			e.printStackTrace();
			throw new SerializationException("The variable with the name: \"" + varName + "\" for the application: \"" + appName + 
											 "\" could not be updated with the serialized value of the input object type: \"" + 
											 varValue.getClass().getCanonicalName() + "\".");
		}
    }
	
	/**
	 * Adds an observer to a boolean variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addBooleanObserver(Context context, ContentObserver observer, String varName) throws SettingNotFoundException {
		addBooleanObserver(context, observer, context.getPackageName(), varName);
	}
	
	/**
	 * Adds an observer to a boolean variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * The method can also be used to observe the value of a variable shared by multiple applications. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addBooleanObserver(Context context, ContentObserver observer, String appName, String varName) throws SettingNotFoundException {
		Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.BOOLEAN + "/" + appName + "/" +  varName);
		ContentResolver cr = context.getContentResolver();
		
		if (observer != null) {
			cr.registerContentObserver(settingRequest, false, observer);
		} else {
			throw new NullPointerException("No ContentObserver sent as parameter.");
		}
	}
	
	/**
	 * Adds an observer to a enum variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addEnumObserver(Context context, ContentObserver observer, String varName) throws SettingNotFoundException {
		addEnumObserver(context, observer, context.getPackageName(), varName);
	}
	
	/**
	 * Adds an observer to a enum variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * The method can also be used to observe the value of a variable shared by multiple applications. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addEnumObserver(Context context, ContentObserver observer, String appName, String varName) throws SettingNotFoundException {
		Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.ENUM + "/" + appName + "/" +  varName);
		ContentResolver cr = context.getContentResolver();
		
		if (observer != null) {
			cr.registerContentObserver(settingRequest, false, observer);
		} else {
			throw new NullPointerException("No ContentObserver sent as parameter.");
		}
	}
	
	/**
	 * Adds an observer to an integer variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addIntegerObserver(Context context, ContentObserver observer, String varName) throws SettingNotFoundException {
		addIntegerObserver(context, observer, context.getPackageName(), varName);
	}
	
	/**
	 * Adds an observer to an integer variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * The method can also be used to observe the value of a variable shared by multiple applications. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addIntegerObserver(Context context, ContentObserver observer, String appName, String varName) throws SettingNotFoundException {
		Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.INTEGER + "/" + appName + "/" +  varName);
		ContentResolver cr = context.getContentResolver();
		
		if (observer != null) {
			cr.registerContentObserver(settingRequest, false, observer);
		} else {
			throw new NullPointerException("No ContentObserver sent as parameter.");
		}
	}
	
	/**
	 * Adds an observer to an double variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addDoubleObserver(Context context, ContentObserver observer, String varName) throws SettingNotFoundException {
		addDoubleObserver(context, observer, context.getPackageName(), varName);
	}
	
	/**
	 * Adds an observer to an double variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * The method can also be used to observe the value of a variable shared by multiple applications. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addDoubleObserver(Context context, ContentObserver observer, String appName, String varName) throws SettingNotFoundException {
		Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.DOUBLE + "/" + appName + "/" +  varName);
		ContentResolver cr = context.getContentResolver();
		
		if (observer != null) {
			cr.registerContentObserver(settingRequest, false, observer);
		} else {
			throw new NullPointerException("No ContentObserver sent as parameter.");
		}
	}
	
	/**
	 * Adds an observer to a String variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addStringObserver(Context context, ContentObserver observer, String varName) throws SettingNotFoundException {
		addStringObserver(context, observer, context.getPackageName(), varName);
	}
	
	/**
	 * Adds an observer to a String variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * The method can also be used to observe the value of a variable shared by multiple applications. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addStringObserver(Context context, ContentObserver observer, String appName, String varName) throws SettingNotFoundException {
		Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.STRING + "/" + appName + "/" +  varName);
		ContentResolver cr = context.getContentResolver();
		
		if (observer != null) {
			cr.registerContentObserver(settingRequest, false, observer);
		} else {
			throw new NullPointerException("No ContentObserver sent as parameter.");
		}
	}
	
	/**
	 * Adds an observer to an Object variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addObjectObserver(Context context, ContentObserver observer, String varName, Class<Serializable> someClass) throws SettingNotFoundException {
		addObjectObserver(context, observer, context.getPackageName(), varName, someClass);
	}
	
	/**
	 * Adds an observer to an Object variable. The observers <code>onChange</code> method will be called when the observed variable is updated.
	 * The provided observer must extend <code>ContentObserver</code> for this to work.
	 * The method can also be used to observe the value of a variable shared by multiple applications. 
	 * @param context The context of your application.
	 * @param observer The observer.
	 * @param appName The name of some application.
	 * @param varName The name of the variable to observe.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void addObjectObserver(Context context, ContentObserver observer, String appName, String varName, Class<Serializable> someClass) throws SettingNotFoundException {
		Uri settingRequest = Uri.withAppendedPath(PrivateDefinitions.CONTENT_URI, PrivateDefinitions.OBJECT + "/" + appName + "/" +  varName + "/" + someClass.getCanonicalName());
		ContentResolver cr = context.getContentResolver();
		
		if (observer != null) {
			cr.registerContentObserver(settingRequest, false, observer);
		} else {
			throw new NullPointerException("No ContentObserver sent as parameter.");
		}
	}
	
	/**
	 * Removes an observer.
	 * @param context The context of your application.
	 * @param observer The observer to remove.
	 * @throws SettingNotFoundException If the combination of application- and variable name is not found in the administration database.
	 */
	public static void removeObserver(Context context, ContentObserver observer) throws SettingNotFoundException {
		ContentResolver cr = context.getContentResolver();
		
		if (observer != null) {
			cr.unregisterContentObserver(observer);
		} else {
			throw new NullPointerException("No ContentObserver sent as parameter.");
		}
	}
}
