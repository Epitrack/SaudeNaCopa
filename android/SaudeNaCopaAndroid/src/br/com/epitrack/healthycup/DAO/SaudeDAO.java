package br.com.epitrack.healthycup.DAO;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

public class SaudeDAO {
	private static String PATH_SERVIDOR = "http://www.saudenacopa.com/proxyTwitter/";

	public String buscaTwitter(String idTwitter) {

		HttpClient httpclient = new DefaultHttpClient();

		HttpGet httpget = new HttpGet(PATH_SERVIDOR + "?id=" + idTwitter);
		HttpResponse response;
		try {
			response = httpclient.execute(httpget);			
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				// json is UTF-8 by default
				BufferedReader reader = new BufferedReader(new InputStreamReader(instream, "UTF-8"), 8);
				StringBuilder sb = new StringBuilder();

				String line = null;
				while ((line = reader.readLine()) != null) {
					sb.append(line + "\n");
				}
				String result = sb.toString();
				result = result.substring(1, result.length()-1);
				result = "{\"results\" : ["+result+"}";
				return result;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {
		}
		return null;
	}
}
