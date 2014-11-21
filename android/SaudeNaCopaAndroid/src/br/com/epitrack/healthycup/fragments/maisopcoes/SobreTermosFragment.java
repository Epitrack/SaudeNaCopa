package br.com.epitrack.healthycup.fragments.maisopcoes;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ExpandableListAdapter;
import android.widget.ExpandableListView;
import android.widget.TextView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.adapters.ConsuladoAdapter;

public class SobreTermosFragment extends Fragment {

	public SobreTermosFragment() {

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
			view = inflater.inflate(R.layout.fragment_sobre_termos, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}
		expListView = (ExpandableListView) view.findViewById(R.id.expandableListView1);
		// busca a query
		Bundle args = getArguments();
		String pagina = args.getString("pagina");
		if(pagina.equals("sobre")){
			getItens();
		}else{
			getItensTermos();
		}
		

		return view;
	}
	
	public void getItensTermos() {

		View header = View.inflate(getActivity(), R.layout.list_consulado_item, null);
		TextView txtHeader = (TextView) header.findViewById(R.id.lblListItem);
		txtHeader.setText(getString(R.string.termos1));
		txtHeader.setTextColor(getResources().getColor(R.color.azul_texto));
		expListView.addHeaderView(header);

		listDataHeader = new ArrayList<String>();
		listDataChild = new HashMap<String, List<String>>();

		listDataHeader.add(getString(R.string.termos_2_titulo));
		List<String> end = new ArrayList<String>();
		end.add(getString(R.string.termos_2));
		listDataChild.put(listDataHeader.get(0), end);

		listDataHeader.add(getString(R.string.termos_3_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.sobre_3));
		listDataChild.put(listDataHeader.get(1), end);

		listDataHeader.add(getString(R.string.termos_4_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_4));
		listDataChild.put(listDataHeader.get(2), end);

		listDataHeader.add(getString(R.string.termos_5_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_5));
		listDataChild.put(listDataHeader.get(3), end);
		
		listDataHeader.add(getString(R.string.termos_6_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_6));
		listDataChild.put(listDataHeader.get(4), end);
		
		listDataHeader.add(getString(R.string.termos_7_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_7));
		listDataChild.put(listDataHeader.get(5), end);
		
		listDataHeader.add(getString(R.string.termos_8_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_8));
		listDataChild.put(listDataHeader.get(6), end);
		
		listDataHeader.add(getString(R.string.termos_9_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_9));
		listDataChild.put(listDataHeader.get(7), end);
		
		listDataHeader.add(getString(R.string.termos_10_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_10));
		listDataChild.put(listDataHeader.get(8), end);
		
		listDataHeader.add(getString(R.string.termos_11_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_11));
		listDataChild.put(listDataHeader.get(9), end);
		
		listDataHeader.add(getString(R.string.termos_12_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_12));
		listDataChild.put(listDataHeader.get(10), end);
		
		listDataHeader.add(getString(R.string.termos_13_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_13));
		listDataChild.put(listDataHeader.get(11), end);
		
		listDataHeader.add(getString(R.string.termos_14_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.termos_14));
		listDataChild.put(listDataHeader.get(12), end);

		listAdapter = new ConsuladoAdapter(view.getContext(), listDataHeader, listDataChild, R.color.azul_texto);
		// setting list adapter
		expListView.setAdapter(listAdapter);

	}

	public void getItens() {

		View header = View.inflate(getActivity(), R.layout.list_consulado_item, null);
		TextView txtHeader = (TextView) header.findViewById(R.id.lblListItem);
		txtHeader.setText(getString(R.string.sobre_1));
		txtHeader.setTextColor(getResources().getColor(R.color.azul_texto));
		expListView.addHeaderView(header);

		listDataHeader = new ArrayList<String>();
		listDataChild = new HashMap<String, List<String>>();

		listDataHeader.add(getString(R.string.sobre_2_titulo));
		List<String> end = new ArrayList<String>();
		end.add(getString(R.string.sobre_2));
		listDataChild.put(listDataHeader.get(0), end);

		listDataHeader.add(getString(R.string.sobre_3_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.sobre_3));
		listDataChild.put(listDataHeader.get(1), end);

		listDataHeader.add(getString(R.string.sobre_4_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.sobre_4));
		listDataChild.put(listDataHeader.get(2), end);

		listDataHeader.add(getString(R.string.sobre_5_titulo));
		end = new ArrayList<String>();
		end.add(getString(R.string.sobre_5));
		listDataChild.put(listDataHeader.get(3), end);

		listAdapter = new ConsuladoAdapter(view.getContext(), listDataHeader, listDataChild, R.color.azul_texto);
		// setting list adapter
		expListView.setAdapter(listAdapter);

	}

}
