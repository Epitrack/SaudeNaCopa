package br.com.epitrack.healthycup.fragments.saude;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.location.Location;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Toast;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.DAO.MapDAO;
import br.com.epitrack.healthycup.DAO.UserDAO;
import br.com.epitrack.healthycup.classes.Usuario;
import br.com.epitrack.healthycup.util.PreferenciasUtil;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.OnMarkerClickListener;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

public class MapaFragment extends Fragment {

	public MapaFragment() {

	}

	private GoogleMap gMap;
	private static View view;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		if (view != null) {
			ViewGroup parent = (ViewGroup) view.getParent();
			if (parent != null)
				parent.removeView(view);
		}
		try {
			view = inflater.inflate(R.layout.mapa_fragment, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}

		if (gMap == null) {
			gMap = ((MapFragment) getActivity().getFragmentManager().findFragmentById(R.id.map)).getMap();
		}

		Location location = new Usuario().getLocalizacao(getActivity().getApplicationContext());

		double lat = location.getLatitude();
		double lng = location.getLongitude();

		LatLng myLocation = new LatLng(lat, lng);
		try {
			gMap.setMyLocationEnabled(true);
			gMap.moveCamera(CameraUpdateFactory.newLatLngZoom(myLocation, 11));

			gMap.clear();
			gMap.addMarker(new MarkerOptions().icon(BitmapDescriptorFactory.fromResource(R.drawable.ico_mapa_masc)).position(myLocation));

			// busca a query
			Bundle args = getArguments();
			String query = args.getString("query");
			//
			// // fazer requisicao
			// //
			// https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-15.80760,-47.96335&radius=5000&keyword=hospital&sensor=false&key=AIzaSyCqn0s3iFP53fhhHB-6c2-zT5HTpoi8Lqk
			new BuscarHospitais(gMap, query, myLocation, (ProgressBar) getActivity().findViewById(R.id.progressBar1)).execute();
			LinearLayout ln = (LinearLayout) view.findViewById(R.id.lnLegenda);
			if (query.equals("Hospital")) {
				String hospitais = new PreferenciasUtil().getHospitais(getActivity().getApplicationContext());
				if(hospitais==null){
					new BuscarHospitaisReferencia().execute();
				}else{
					plotaHospitaisReferencia(hospitais);
				}
				
				ln.setVisibility(View.VISIBLE);
			} else {				
				ln.setVisibility(View.INVISIBLE);
			}
		} catch (Exception e) {
			Toast.makeText(getActivity(), getString(R.string.sem_localizacao), Toast.LENGTH_LONG).show();
		}
		

		return view;
	}

	Marker mk;

	public void plotaHospitaisReferencia(String result) {
		JSONObject jObject;

		try {
			jObject = new JSONObject(result);
			JSONArray jArray = jObject.getJSONArray("dados");

			for (int i = 0; i < jArray.length(); i++) {
				String obj2 = jArray.getString(i);
				String[] itens = obj2.split("\",");
				String UF = itens[0].substring(2, 4);
				String cidade = itens[1].substring(1);
				String nome = itens[2].substring(1);
				String tel = itens[3].substring(1);
				String end = itens[4].substring(1);
				String local = itens[5].substring(1, itens[5].length() - 2);
				String[] localArray = local.split(",");
				if (localArray.length > 1) {

					LatLng myLocation = new LatLng(Double.parseDouble(localArray[0]), Double.parseDouble(localArray[1]));

					gMap.addMarker(new MarkerOptions().icon(BitmapDescriptorFactory.fromResource(R.drawable.ico_mapa_hosp_ref))
							.title(end + "\n" + tel + "\n" + cidade + "-" + UF).snippet(nome).position(myLocation));

					gMap.setOnMarkerClickListener(new OnMarkerClickListener() {

						@Override
						public boolean onMarkerClick(Marker marker) {
							mk = marker;
							new AlertDialog.Builder(getActivity()).setTitle(marker.getSnippet()).setMessage(marker.getTitle())
									.setPositiveButton(getString(R.string.abrir), new DialogInterface.OnClickListener() {
										public void onClick(DialogInterface dialog, int which) {

											String uri = "geo:" + String.valueOf(mk.getPosition().latitude) + ","
													+ String.valueOf(mk.getPosition().longitude) + "?q=" + mk.getSnippet();

											// Log.i(SentimentoActivity.TAG,uri);
											startActivity(new Intent(android.content.Intent.ACTION_VIEW, Uri.parse(uri)));
										}
									}).setNegativeButton(android.R.string.cancel, new DialogInterface.OnClickListener() {
										public void onClick(DialogInterface dialog, int which) {

										}
									}).show();

							return true;
						}

					});
				}
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

	public class BuscarHospitaisReferencia extends AsyncTask<Void, Void, String> {

		public BuscarHospitaisReferencia() {

		}

		@Override
		protected String doInBackground(Void... arg0) {

			return new UserDAO().buscaHospitaisReferencia();
		}

		protected void onPostExecute(String result) {
			new PreferenciasUtil().salvaHospitais(result, getActivity().getApplicationContext());
			plotaHospitaisReferencia(result);

		}

	}

	/**
	 * BuscarHospitais
	 * 
	 * @author Guto
	 * 
	 */

	public class BuscarHospitais extends AsyncTask<Void, Void, String> {

		private GoogleMap gmap;
		private String query;
		private LatLng myLocation;
		private ProgressBar pb;
		private Marker mk;
		boolean isHospital = false;

		public BuscarHospitais(GoogleMap gmap, String query, LatLng myLocation, ProgressBar progressBar) {
			this.gmap = gmap;
			this.query = query;
			this.myLocation = myLocation;
			pb = progressBar;
			pb.setVisibility(View.VISIBLE);
			if (query.equals("Hospital")) {
				isHospital = true;
			}
		}

		@Override
		protected String doInBackground(Void... arg0) {
			try {
				pb = (ProgressBar) getActivity().findViewById(R.id.progressBar1);
				pb.setVisibility(View.VISIBLE);
			} catch (Exception e) {
				// TODO: handle exception
			}
			
			return new MapDAO().buscaMapa(query, myLocation);
		}

		protected void onPostExecute(String result) {
			try {
				JSONObject jObject = new JSONObject(result);
				JSONArray jArray = jObject.getJSONArray("results");
				for (int i = 0; i < jArray.length(); i++) {
					JSONObject obj = jArray.getJSONObject(i);
					String nome = obj.getString("name");
					String end = obj.getString("vicinity");

					JSONObject geo = obj.getJSONObject("geometry");
					JSONObject location = geo.getJSONObject("location");
					location.getDouble("lat");
					location.getDouble("lng");
					LatLng myLocation = new LatLng(location.getDouble("lat"), location.getDouble("lng"));
					if (isHospital) {
						gmap.addMarker(new MarkerOptions().icon(BitmapDescriptorFactory.fromResource(R.drawable.ico_mapa_hosp_google)).title(end)
								.snippet(nome).position(myLocation));
					} else {
						gmap.addMarker(new MarkerOptions().icon(BitmapDescriptorFactory.fromResource(R.drawable.ico_mapa_farmacia)).title(end)
								.snippet(nome).position(myLocation));
					}

					gmap.setOnMarkerClickListener(new OnMarkerClickListener() {

						@Override
						public boolean onMarkerClick(Marker marker) {
							mk = marker;
							new AlertDialog.Builder(getActivity()).setTitle(marker.getSnippet()).setMessage(marker.getTitle())
									.setPositiveButton(getString(R.string.abrir), new DialogInterface.OnClickListener() {
										public void onClick(DialogInterface dialog, int which) {

											String uri = "geo:" + String.valueOf(mk.getPosition().latitude) + ","
													+ String.valueOf(mk.getPosition().longitude) + "?q=" + mk.getSnippet();

											// Log.i(SentimentoActivity.TAG,uri);
											startActivity(new Intent(android.content.Intent.ACTION_VIEW, Uri.parse(uri)));
										}
									}).setNegativeButton(android.R.string.cancel, new DialogInterface.OnClickListener() {
										public void onClick(DialogInterface dialog, int which) {

										}
									}).show();

							return true;
						}

					});
				}

			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			try {
				pb.setVisibility(View.INVISIBLE);
			} catch (Exception e) {
				// TODO: handle exception
			}
			
		}
	}

}