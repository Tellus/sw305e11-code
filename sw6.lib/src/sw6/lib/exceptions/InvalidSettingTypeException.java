package sw6.lib.exceptions;

import sw6.lib.internal.PrivateDefinitions;

public class InvalidSettingTypeException extends IllegalArgumentException {

	private static final long serialVersionUID = 1L;

	public InvalidSettingTypeException(String appName, String varName, String dataType)	{
		super("The datatype: \"" + dataType + "\" is not a valid datatype to store in the administration database. " +
				"Please check if the correct datatype has been specified for variable: \"" + varName + "\" in " +
				"the \"" + PrivateDefinitions.SETTINGS_FILE_NAME + "\" file for application: \"" + appName + "\".");
	}
}
