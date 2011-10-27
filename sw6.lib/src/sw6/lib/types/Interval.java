package sw6.lib.types;

import java.io.Serializable;
import java.util.Iterator;

/**
 * The purpose of this class is to represent an interval of integers 
 * as a stdobject in the settings.xml file. An interval is defined 
 * with two integers where the integers are between a given min and 
 * max integer values.
 * 
 * @author sw6b
 */
public class Interval implements Serializable, Iterable<Integer> {
	private static final long serialVersionUID = 1L;
	private int _from;
	private int _to;
	private Interval _limitInterval;
	
	/**
	 * Internal constructor used to make an instance of the Interval 
	 * class without an min or max value. This constructor is made 
	 * because we need it to make the internal interval we use to 
	 * represent min and max value (the _limitInterval variable).
	 * <br/><br/>
	 * An interval is defined with two integers (<b>from</b> and 
	 * <b>to</b>) where <b>from</b> and <b>to</b> is included in 
	 * the interval.
	 * 
	 * @param from Set the starting point of the interval.
	 * @param to Set the ending point of the interval.
	 */
	private Interval(int from, int to) {
		set(from, to);
	}
	
	/**
	 * Constructs an instance of the interval class.
	 * <br/><br/>
	 * An interval is defined with two integers (<b>from</b> and 
	 * <b>to</b>) where <b>from</b> and <b>to</b> is included in 
	 * the interval. Also, <b>to</b> and <b>from</b> values need  
	 * to be between the given <b>min</b> and <b>max</b> values.
	 * 
	 * @param from Set the starting point of the interval.
	 * @param to Set the ending point of the interval.
	 * @param min Specify the minimum value that can be used in the interval.
	 * @param max Specify the maximum value that can be used in the interval.
	 */
	public Interval(int from, int to, int min, int max) {
		_limitInterval = new Interval(min, max);
		set(from, to);
	}
	
	/**
	 * Get the smallest value allowed in the interval.
	 * @return Minimum value of the interval.
	 */
	public int getMin() {
		if (_limitInterval != null) {
			return _limitInterval.getFrom();
		}
		else {
			/* This is needed because we use an interval to indicate the min
			 * and max value of the 'from' and 'to' in the interval.
			 */
			return Integer.MIN_VALUE;
		}
	}
	
	/**
	 * Get the highest value allowed in the interval.
	 * @return Maximum value of the interval.
	 */
	public int getMax() {
		if (_limitInterval != null) {
			return _limitInterval.getTo();
		}
		else {
			/* This is needed because we use an interval to indicate the min
			 * and max value of the 'from' and 'to' in the interval.
			 */
			return Integer.MAX_VALUE;
		}
	}
	
	/**
	 * Get the first value of the interval.
	 * @return Start of the interval.
	 */
	public int getFrom() {
		return _from;
	}
	
	/**
	 * Get the last value of the interval.
	 * @return End of the interval.
	 */
	public int getTo() {
		return _to;
	}
	
	/**
	 * Sets a new value for <b>from</b> and <b>to</b> there
	 * define the interval. The new values need to be between
	 * the minimum and maximum values defined at the object
	 * initialization.
	 * @param from Set the starting point of the interval.
	 * @param to Set the ending point of the interval.
	 */
	public void set(int from, int to) {
		if (from < getMin() || from > getMax()) {
			throw new IllegalArgumentException("from = " + from + " is not between min = " + getMin() + " and max = " + getMax() + ".");
		}
		
		if (to < getMin() || to > getMax()) {
			throw new IllegalArgumentException("to = " + to + " is not between min = " + _limitInterval.getFrom() + " and max = " + _limitInterval.getTo() + ".");
		}
		
		/*
		 * This is not a documented feature but if the 
		 * developers used our class wrong and swap
		 * the value of 'from' and 'to' or 'min' and
		 * 'max' we make the "service" and swap the 
		 * values so the interval object is still 
		 * working.
		 */
		if(from > to) {
			_from = to;
			_to = from;
		} else {
			_from = from;
			_to = to;
		}
	}
	
	/**
	 * Get the size of the interval. As an example the size of 
	 * the interval 1-3 is 3 because we have 1, 2 and 3 in the 
	 * interval.
	 * @return Size of the interval.
	 */
	public int size() {
		return getTo() - getFrom() + 1;
	}
	
	/**
	 * Check if the interval contains the integer <b>check</b>. As
	 * an example the number 1, 2 and 3 is in the interval 1-3 but
	 * not 4 or 0.
	 * @param check Interger to check.
	 * @return True if the interger <b>check</b> is in the interval.
	 */
	public boolean contains(int check) {
		return (getFrom() <= check && check <= getTo()) ? true : false;
	}
	
	/**
	 * Generate an array of intergers there contains all numbers in
	 * the interval. As an example the interval 1-3 will generate an
	 * array with a size of 3 integers and contain 1, 2 and 3. 
	 * @return Array of integers containing the numbers in the interval.
	 */
	public int[] toArray() {
		int[] array = new int[this.size()];
		
		for (int i = 0; i < array.length; i++) {
			array[i] = getFrom() + i;
		}
		return array;
	}

	@Override
	public boolean equals(Object o) {
		if (o instanceof Interval) {
			Interval inter = Interval.class.cast(o);
			
			if (inter._limitInterval != null && !inter._limitInterval.equals(_limitInterval)) {
				return false;
			}
			
			if (inter.getFrom() == this.getFrom() && inter.getTo() == this.getTo()) {
				return true;
			}
		}
		return false;			
	}
	
	/**
	 * Class to be used if we want to iterate over the interval.
	 * @author sw6b
	 */
	private class Itr implements Iterator<Integer> {
		int _cursor = getFrom();

		@Override
		public boolean hasNext() {
			return (contains(_cursor)) ? true : false;
		}

		@Override
		public Integer next() {
			return _cursor++;
		}

		@Override
		public void remove() {
			throw new UnsupportedOperationException();
		}
	}

	@Override
	public Iterator<Integer> iterator() {
		return new Itr();
	}
}
