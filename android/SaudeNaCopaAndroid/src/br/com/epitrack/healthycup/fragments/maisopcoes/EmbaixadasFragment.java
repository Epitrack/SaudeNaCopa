package br.com.epitrack.healthycup.fragments.maisopcoes;

import java.io.IOException;
import java.io.InputStream;
import java.net.URLDecoder;
import java.text.Collator;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;

import org.json.JSONException;
import org.json.JSONObject;

import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ExpandableListAdapter;
import android.widget.ExpandableListView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.DAO.UserDAO;
import br.com.epitrack.healthycup.adapters.ConsuladoAdapter;

public class EmbaixadasFragment extends Fragment {

	public EmbaixadasFragment() {

	}

	private static View view;
	ExpandableListAdapter listAdapter;
	ExpandableListView expListView;
	List<String> listDataHeader;
	HashMap<String, List<String>> listDataChild;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		if (view != null) {
			ViewGroup parent = (ViewGroup) view.getParent();
			if (parent != null)
				parent.removeView(view);
		}
		try {
			view = inflater.inflate(R.layout.fragment_consulado, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}
		expListView = (ExpandableListView) view.findViewById(R.id.lvExp);
		// prepareListData();
		// String consulados = new
		// PreferenciasUtil().getConsulados(getActivity().getApplicationContext());
		String consulados = getJson();
		if (consulados == null) {
			new BuscaConsulados().execute();
		} else {
			getConsulados(consulados);
		}
		

		return view;
	}

	public String getJson() {
		String bufferString = "";
		InputStream is;
		try {
			// String content = Files.toString(new File(filename),
			// StandardCharsets.ISO_8859_1);

			is = getActivity().getAssets().open("embaixadas.json");
			int size = is.available();
			byte[] buffer = new byte[size];
			is.read(buffer);
			is.close();
			bufferString = new String(buffer);
			bufferString = URLDecoder.decode(bufferString, "UTF-8");

		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return bufferString;
	}

	public void getConsulados(String result) {
		listDataHeader = new ArrayList<String>();
		listDataChild = new HashMap<String, List<String>>();

		try {
			JSONObject obj = new JSONObject(result);
			Iterator<String> keys = obj.keys();
			int i = 0;
			while (keys.hasNext()) {
				String pais = (String) keys.next();
				// buscar nome do pais em outro idioma
				// se ingles adicionar nome do pais correto
//				List<NameValuePair> paisesOrdemInvertida = new Util().inverteOrdem();
				listDataHeader.add(pais);
				JSONObject cidadesObj = obj.getJSONObject(pais);

				Iterator<String> keysCidades = cidadesObj.keys();
				List<String> end = new ArrayList<String>();
				while (keysCidades.hasNext()) {
					String cidade = (String) keysCidades.next();
					end.add(cidade + "\n" + cidadesObj.getString(cidade));
				}
				listDataChild.put(listDataHeader.get(i), end);
				i++;

			}

			Collections.sort(listDataHeader, Collator.getInstance());
			listAdapter = new ConsuladoAdapter(view.getContext(), listDataHeader, listDataChild, R.color.cinza_texbox);
			// setting list adapter
			expListView.setAdapter(listAdapter);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		// catch (UnsupportedEncodingException e) {
		// // TODO Auto-generated catch block
		// e.printStackTrace();
		// }
	}

	// se precisar buscar no site

	public class BuscaConsulados extends AsyncTask<Void, Void, String> {

		public BuscaConsulados() {

		}

		@Override
		protected String doInBackground(Void... arg0) {

			return new UserDAO().buscaConsulados();
		}

		protected void onPostExecute(String result) {
			getConsulados(result);

		}

	}

}
