
package sw6.lib.user;

import java.util.Calendar;
import java.util.GregorianCalendar;

import sw6.lib.Settings;
import sw6.lib.internal.PrivateDefinitions;
import android.content.Context;


/**
 * This class is used to retrieve information about the user profile. When a piece of information is
 * requested (eg. the getBirthDay method is called), the information is loaded directly from the database without caching.
 * In other word, Profile does not serve as a data container, but mearly as a bridge between the developer and the database.
 * @author sw6b
 *
 */
public class Profile {
	public enum Gender {MALE, FEMALE};

	Context mContext;

	/**
	 * Creates an instance of profile
	 * @param context The context of the app, requesting the profile.
	 */
	public Profile(Context context) {
		mContext = context;
	}

	/**
	 * Gets the name of the user
	 * @return The name of the user as a String
	 */
	public String getName() {
		return Settings.getString(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_NAME);
	}

	/**
	 * Gets the day of birth of the user
	 * @return The day day of birth of the user as a Date object
	 */
	public Calendar getBirthDay() {
		int day = Settings.getInteger(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_BIRTH_DAY);
		int month = Settings.getInteger(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_BIRTH_MONTH);
		int year = Settings.getInteger(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_BIRTH_YEAR);
		Calendar date = new GregorianCalendar(year, month-1, day);
		return date;
	}

	/**
	 * Gets gender of the user
	 * @return The gender as an enum element of enum Gender
	 */
	public Gender getGender() {
		int gender = Settings.getEnum(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_GENDER);
		if (gender == 0)
			return Gender.MALE;
		else
			return Gender.FEMALE;
	}

	/**
	 * Gets the address of the user
	 * @return The address of the user as a String
	 */
	public String getAddress() {
		return Settings.getString(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_ADDRESS);
	}

	/**
	 * Gets the phonenumber of the user
	 * @return The phonenumber of the user as a String
	 */
	public String getPhoneNumber() {
		return Settings.getString(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_PHONE_NUMBER);
	}

	/**
	 * Gets if the user can drag and drop
	 * @return A true false statement
	 */
	public boolean canDragAndDrop() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_DRAG_DROP);
	}

	/**
	 * Gets if the user can hear
	 * @return A true false statement
	 */
	public boolean canHear() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_HEAR);
	}

	/**
	 * Gets if the user can analog time
	 * @return A true false statement
	 */
	public boolean canAnalogTime() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_ANALOG_TIME);
	}

	/**
	 * Gets if the user can digital time
	 * @return A true false statement
	 */
	public boolean canDigitalTime() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_DIGITAL_TIME);
	}

	/**
	 * Gets if the user can read
	 * @return A true false statement
	 */
	public boolean canRead() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_READ);
	}

	/**
	 * Gets if the user requres large buttons
	 * @return A true false statement
	 */
	public boolean requresLargeButtons() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_REQURES_LARGE_BUTTONS);
	}

	/**
	 * Gets if the user can speak
	 * @return A true false statement
	 */
	public boolean canSpeak() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_SPEAK);
	}

	/**
	 * Gets if the user understands numbers
	 * @return A true false statement
	 */
	public boolean canNumbers() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_NUMBERS);
	}

	/**
	 * Gets if the user can use a keyboard
	 * @return A true false statement
	 */
	public boolean canUseKeyboard() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_CAN_USE_KEAYBOARD);
	}
	
	/**
	 * Gets if the user can requres simple visual effects
	 * @return A true false statement
	 */
	public boolean requreSimpleVisualEffects() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_REQURES_SIMPLE_VISUAL_EFFECTS);
	}
	
	/**
	 * Gets if the user can requres simple visual effects
	 * @return A true false statement
	 */
	public boolean hasBadVision() {
		return Settings.getBoolean(mContext, PrivateDefinitions.SW6_ADMIN_PACKAGE_NAME, PrivateDefinitions.PROFILE_HAS_BAD_VISION);
	}
}