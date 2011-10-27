package sw6.lib.girafplace;

import java.io.IOException;
import java.io.Serializable;

/**
 * The settings, that are available in the UserProfile, accessible from 
 * the administration interface of the Giraf Launcher. 
 * These settings are capabilities/handicaps a user have, 
 * so that only applications matching the UserProfile are being shown.
 * NOTE: These are boolean values, meaning 0 = deactivated setting, 1 = activated setting.
 * @author SW6C
 */
public class UserProfile implements Serializable{
	//Auto-generated, in order to make this class serializable.
	private static final long serialVersionUID = -2495039756759385742L;
	
	public boolean canDragAndDrop;
	public boolean canHear;
	public boolean requiresSimpleVisualEffects;
	public boolean canAnalogTime;
	public boolean canDigitalTime;
	public boolean canRead;
	public boolean hasBadVision;
	public boolean requiresLargeButtons;
	public boolean canSpeak;
	public boolean canNumbers;
	public boolean canUseKeyboard;
	/**
	 * The getter for the canDragAndDrop setting.
	 * @return the canDragAndDrop boolean value. (0 = Cannot use drag and drop, 
	 * 1 = Can use drag and drop.)
	 */
	public boolean canDragAndDrop() {
		return canDragAndDrop;
	}
	/**
	 * The setter for the canDragAndDrop setting.
	 * @param canDragAndDrop The canDragAndDrop boolean value to set.
	 * (0 = Cannot use drag and drop, 1 = Can use drag and drop.)
	 */
	public void setCanDragAndDrop(boolean canDragAndDrop) {
		this.canDragAndDrop = canDragAndDrop;
	}
	/**
	 * The getter for the canHear setting.
	 * @return The canHear boolean value. (0 = Cannot hear, 1 = Can hear.)
	 */
	public boolean canHear() {
		return canHear;
	}
	/**
	 * The setter for the canHear setting.
	 * @param canHear The canHear boolean value to set.
	 * (0 = Cannot hear, 1 = Can hear.)
	 */
	public void setCanHear(boolean canHear) {
		this.canHear = canHear;
	}
	/**
	 * The getter for the requiresSimpleVisualEffects setting.
	 * @return The requiresSimpleVisualEffects boolean value.
	 * (0 = Does not require a simple interface, 1 = Requires a simple interface.)
	 */
	public boolean requiresSimpleVisualEffects() {
		return requiresSimpleVisualEffects;
	}
	/**
	 * The setter for the requiresSimpleVisualEffects setting.
	 * @param requiresSimpleVisualEffects The requiresSimpleVisualEffects 
	 * boolean value to set. (0 = Does not require a simple interface, 
	 * 1 = Requires a simple interface.)
	 */
	public void setRequiresSimpleVisualEffects(boolean requiresSimpleVisualEffects) {
		this.requiresSimpleVisualEffects = requiresSimpleVisualEffects;
	}
	/**
	 * The getter for the canAnalogTime setting.
	 * @return The canAnalogTime boolean value.
	 * (0 = Cannot read an analog clock, 1 = Can read an analog clock.)
	 */
	public boolean canAnalogTime() {
		return canAnalogTime;
	}
	/**
	 * The setter for the canAnalogTime setting.
	 * @param canAnalogTime The canAnalogTime boolean value to set.
	 * (0 = Cannot read an analog clock, 1 = Can read an analog clock.)
	 */
	public void setCanAnalogTime(boolean canAnalogTime) {
		this.canAnalogTime = canAnalogTime;
	}
	/**
	 * The getter for the canDigitalTime setting.
	 * @return The canDigitalTime boolean value.
	 * (0 = Cannot read an digital clock, 1 = Can read an digital clock.)
	 */
	public boolean canDigitalTime() {
		return canDigitalTime;
	}
	/**
	 * The setter for the canDigitalTime setting.
	 * @param canDigitalTime The canDigitalTime boolean value to set.
	 * (0 = Cannot read an digital clock, 1 = Can read an digital clock.)
	 */
	public void setCanDigitalTime(boolean canDigitalTime) {
		this.canDigitalTime = canDigitalTime;
	}
	/**
	 * The getter for the canRead setting.
	 * @return The canRead boolean value. (0 = Cannot read, 1 = Can read.)
	 */
	public boolean canRead() {
		return canRead;
	}
	/**
	 * The setter for the canRead setting.
	 * @param canRead The canRead boolean value to set. (0 = Cannot read, 
	 * 1 = Can read.)
	 */
	public void setCanRead(boolean canRead) {
		this.canRead = canRead;
	}
	/**
	 * The getter for the hasBadVision setting.
	 * @return The hasBadVision boolean value.
	 * (0 = Does not have a bad vision , 1 = Does have a bad vision.)
	 */
	public boolean hasBadVision() {
		return hasBadVision;
	}
	/**
	 * The setter for the hasBadvision setting.
	 * @param hasBadVision The hasBadVision boolean value to set.
	 * (0 = Does not have a bad vision , 1 = Does have a bad vision.)
	 */
	public void setHasBadVision(boolean hasBadVision) {
		this.hasBadVision = hasBadVision;
	}
	/**
	 * The getter for the requiresLargeButtons setting.
	 * @return The requiresLargeButtons boolean value.
	 * (0 = Does not require larger buttons , 1 = Does require larger buttons.)
	 */
	public boolean requiresLargeButtons() {
		return requiresLargeButtons;
	}
	/**
	 * The setter for the requiresLargeButtons setting.
	 * @param requiresLargeButtons The requiresLargeButtons boolean value to set.
	 * (0 = Does not require larger buttons , 1 = Does require larger buttons.)
	 */
	public void setRequiresLargeButtons(boolean requiresLargeButtons) {
		this.requiresLargeButtons = requiresLargeButtons;
	}
	/**
	 * The getter for the canSpeak setting.
	 * @return The canSpeak boolean value.
	 * (0 = Is not able to speak simple sentences, 
	 * 1 = Is able to speak simple sentences.)
	 */
	public boolean canSpeak() {
		return canSpeak;
	}
	/**
	 * The setter for the canSpeak setting.
	 * @param canSpeak The canSpeak boolean value to set.
	 * (0 = Is not able to speak simple sentences, 
	 * 1 = Is able to speak simple sentences.)
	 */
	public void setCanSpeak(boolean canSpeak) {
		this.canSpeak = canSpeak;
	}
	/**
	 * The getter for the canNumbers setting.
	 * @return The canNumbers boolean value.
	 * (0 = Is not able to count numbers, 1 = Is able to count numbers.)
	 */
	public boolean canNumbers() {
		return canNumbers;
	}
	/**
	 * The setter for the canNumbers setting.
	 * @param canNumbers The canNumbers boolean value to set.
	 * (0 = Is not able to count numbers, 1 = Is able to count numbers.)
	 */
	public void setCanNumbers(boolean canNumbers) {
		this.canNumbers = canNumbers;
	}
	/**
	 * The getter for the canUseKeyboard setting.
	 * @return The canUseKeyboard boolean value.
	 * (0 = Is not able to use the standard keyboard on the device, 
	 * 1 = Is able to use the standard keyboard on the device.)
	 */
	public boolean canUseKeyboard() {
		return canUseKeyboard;
	}
	/**
	 * The setter for the canUseKeyboard setting.
	 * @param canUseKeyboard The canUseKeyboard boolean value to set.
	 * (0 = Is not able to use the standard keyboard on the device, 
	 * 1 = Is able to use the standard keyboard on the device.)
	 */
	public void setCanUseKeyboard(boolean canUseKeyboard) {
		this.canUseKeyboard = canUseKeyboard;
	}
	
	/**
	 * Is responsible for writing the state of the object for its particular class 
	 * so that the corresponding readObject method can restore it, thus making it possible
	 * to write data over a network, through an ObjectOutputStream.
	 * @param out The UserProfile about to be sent over a network.
	 * @throws IOException
	 */
	private void writeObject(java.io.ObjectOutputStream out) throws IOException {
		out.writeBoolean(canDragAndDrop);
		out.writeBoolean(canHear);
		out.writeBoolean(requiresSimpleVisualEffects);
		out.writeBoolean(canAnalogTime);
		out.writeBoolean(canDigitalTime);
		out.writeBoolean(canRead);
		out.writeBoolean(hasBadVision);
		out.writeBoolean(requiresLargeButtons);
		out.writeBoolean(canSpeak);
		out.writeBoolean(canNumbers);
		out.writeBoolean(canUseKeyboard);
		out.flush();
	}
	
	/**
	* Is responsible for restoring the object that had been splitted by the writeObject
	* function, and thus makes it possible to read data over a network, through an ObjectInputStream.
	* @param in The UserProfile about to be received over a network.
	* @throws IOException 
	* @throws ClassNotFoundException 
	*/
	private void readObject(java.io.ObjectInputStream in) throws IOException, ClassNotFoundException {
		canDragAndDrop = in.readBoolean();
		canHear = in.readBoolean();
		requiresSimpleVisualEffects = in.readBoolean();
		canAnalogTime = in.readBoolean();
		canDigitalTime = in.readBoolean();
		canRead = in.readBoolean();
		hasBadVision = in.readBoolean();
		requiresLargeButtons = in.readBoolean();
		canSpeak = in.readBoolean();
		canNumbers = in.readBoolean();
		canUseKeyboard = in.readBoolean();
	}
	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "UserProfile [canAnalogTime=" + canAnalogTime
				+ ", canDigitalTime=" + canDigitalTime + ", canDragAndDrop="
				+ canDragAndDrop + ", canHear=" + canHear + ", canNumbers="
				+ canNumbers + ", canRead=" + canRead + ", canSpeak="
				+ canSpeak + ", canUseKeyboard=" + canUseKeyboard
				+ ", hasBadVision=" + hasBadVision + ", requiresLargeButtons="
				+ requiresLargeButtons + ", requiresSimpleVisualEffects="
				+ requiresSimpleVisualEffects + "]";
	}
}
