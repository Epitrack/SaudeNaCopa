package br.com.epitrack.healthycup.util;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class Validator {

	private Pattern pattern;
	private Matcher matcher;

	

	public Validator() {

	}

	/**
	 * Validate hex with regular expression
	 * 
	 * @param hex
	 *            hex for validation
	 * @return true valid hex, false invalid hex
	 */
	public boolean validate(final String hex, String regex) {
		pattern = Pattern.compile(regex);
		matcher = pattern.matcher(hex);
		return matcher.matches();

	}
}
