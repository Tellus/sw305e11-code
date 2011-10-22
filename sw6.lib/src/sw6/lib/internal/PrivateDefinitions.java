package sw6.lib.internal;

import android.net.Uri;

public final class PrivateDefinitions {
	public static final String PROVIDER_CLASS		= "sw6.admin.settingsprovider";
	public static final Uri    CONTENT_URI			= Uri.parse("content://" + PROVIDER_CLASS); 
	public static final String OBJECT 				= "OBJECT";
	public static final String STDOBJECT 			= "STDOBJECT";
	public static final String BOOLEAN 				= "BOOLEAN";
	public static final String INTEGER 				= "INTEGER";
	public static final String DOUBLE 				= "DOUBLE";
	public static final String STRING 				= "STRING";
	public static final String ENUM 				= "ENUM";
	public static final String ENUM_ELEMENT			= "ELEMENT";
	
	public static final String VAR_VALUE			= "var_value";
	
	public static final String GIRAF_APP_PREFIX		= "sw6.";
	public static final String SETTINGS_FILE_NAME	= "settings.xml";
	
	// Default values
	public static final int		DEFAULT_INTEGER		= 0;
	public static final double	DEFAULT_DOUBLE		= 0.0d;
	public static final boolean	DEFAULT_BOOLEAN		= false;
	public static final String	DEFAULT_STRING		= "";
	public static final int		MIN_STRING_LENGTH	= 0;
	public static final byte[]	DEFAULT_OBJECT		= new byte[0];
	
	public static final String SW6_ADMIN_PACKAGE_NAME = "sw6.admin";
	
	public static final String PROFILE_NAME 							= "name";
	public static final String PROFILE_GENDER 							= "gender";
	public static final String PROFILE_BIRTH_DAY 						= "birthDay";
	public static final String PROFILE_BIRTH_MONTH 						= "birthMonth";
	public static final String PROFILE_BIRTH_YEAR 						= "birthYear";
	public static final String PROFILE_ADDRESS 							= "address";
	public static final String PROFILE_PHONE_NUMBER 					= "phoneNummer";
	public static final String PROFILE_CAN_DRAG_DROP 					= "canDragAndDrop";
	public static final String PROFILE_CAN_HEAR							= "canHear";
	public static final String PROFILE_CAN_ANALOG_TIME 					= "canAnalogTime";
	public static final String PROFILE_CAN_DIGITAL_TIME 				= "canDigitalTime";
	public static final String PROFILE_CAN_READ 						= "canRead";
	public static final String PROFILE_REQURES_LARGE_BUTTONS 			= "requresLargeButtons";
	public static final String PROFILE_CAN_SPEAK 						= "canSpeak";
	public static final String PROFILE_CAN_NUMBERS 						= "canNumbers";
	public static final String PROFILE_CAN_USE_KEAYBOARD 				= "canUseKeyboard";
	public static final String PROFILE_REQURES_SIMPLE_VISUAL_EFFECTS 	= "requresSimpleVisualEffects";
	public static final String PROFILE_HAS_BAD_VISION 					= "hasBadVision";
	
	private PrivateDefinitions() {};
}

