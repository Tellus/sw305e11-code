package sw6.lib.exceptions;

public class SerializationException extends IllegalArgumentException {

	private static final long serialVersionUID = 1L;

	public SerializationException(String message)	{
		super(message);
	}
}
