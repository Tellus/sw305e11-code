package sw6.lib.exceptions;

import sw6.lib.internal.PrivateDefinitions;

public class SettingNotFoundException extends IllegalArgumentException {

	private static final long serialVersionUID = 1L;
	private static final String APP_VAR_NAME_COMBI_NOT_FOUND = "Either a constraint failed or application and variablename combination " +
	"does not exist in the administration database. Please check if the requested setting has been declared " +
	"in your application's \"" + PrivateDefinitions.SETTINGS_FILE_NAME + "\" file.";

	public SettingNotFoundException(String appName, String varName, String dataType)	{
		super("Setting: \"" + varName + "\", of type: \"" + dataType + "\", for app: \"" + appName + "\". " + APP_VAR_NAME_COMBI_NOT_FOUND);
	}

}
