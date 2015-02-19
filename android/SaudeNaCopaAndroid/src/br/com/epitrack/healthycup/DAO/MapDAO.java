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

import com.google.android.gms.maps.model.LatLng;

public class MapDAO {

	public String buscaMapa(String query, LatLng myLocation) {
		HttpClient httpclient = new DefaultHttpClient();
		// https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-15.80760,-47.96335&radius=5000&keyword=hospital&sensor=false&key=AIzaSyCqn0s3iFP53fhhHB-6c2-zT5HTpoi8Lqk
		String url ="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location="+myLocation.latitude+","+myLocation.longitude+"&radius=20000&keyword="+query+"&sensor=false&key=AIzaSyCqn0s3iFP53fhhHB-6c2-zT5HTpoi8Lqk";

		HttpGet httpget = new HttpGet(url);
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
			    while ((line = reader.readLine()) != null)
			    {
			        sb.append(line + "\n");
			    }
			    String result = sb.toString();
			    return result;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {
		}
		return null;
	}
	
	

}
