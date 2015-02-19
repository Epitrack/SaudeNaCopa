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
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.adapters.ArenaAdapter;
import br.com.epitrack.healthycup.classes.Arena;

public class ArenasFragment extends Fragment {

	public ArenasFragment() {

	}

	private static View view;
	ExpandableListAdapter listAdapter;
	ExpandableListView expListView;
	List<String> listDataHeader;
	HashMap<String, Arena> listDataChild;

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
		prepareListData();

		listAdapter = new ArenaAdapter(view.getContext(), listDataHeader, listDataChild);

		// setting list adapter
		expListView.setAdapter(listAdapter);

		return view;
	}

	private void prepareListData() {
		listDataHeader = new ArrayList<String>();
		listDataChild = new HashMap<String, Arena>();

		listDataHeader.add(getString(R.string.arena_amazonia));
		listDataHeader.add(getString(R.string.arena_baixada));
		listDataHeader.add(getString(R.string.arena_fonte_nova));
		listDataHeader.add(getString(R.string.arena_pantanal));
		listDataHeader.add(getString(R.string.arena_pernambuco));
		listDataHeader.add(getString(R.string.arena_sao_paulo));
		listDataHeader.add(getString(R.string.estadio_beira_rio));
		listDataHeader.add(getString(R.string.estadio_castelao));
		listDataHeader.add(getString(R.string.estadio_dunas));
		listDataHeader.add(getString(R.string.estadio_maracana));
		listDataHeader.add(getString(R.string.estadio_mineirao));
		listDataHeader.add(getString(R.string.estadio_nacional));

		// colocar a descricao
		listDataChild.put(listDataHeader.get(0), new Arena(R.string.desc_arena_amazonia, R.drawable.img_amazonia));
		listDataChild.put(listDataHeader.get(1), new Arena(R.string.desc_arena_baixada, R.drawable.img_curitiba));
		listDataChild.put(listDataHeader.get(2), new Arena(R.string.desc_arena_fonte_nova, R.drawable.img_salvador));
		listDataChild.put(listDataHeader.get(3), new Arena(R.string.desc_arena_pantanal, R.drawable.img_cuiaba));
		listDataChild.put(listDataHeader.get(4), new Arena(R.string.desc_arena_pernambuco, R.drawable.img_pe));
		listDataChild.put(listDataHeader.get(5), new Arena(R.string.desc_arena_saopaulo, R.drawable.img_sp));
		listDataChild.put(listDataHeader.get(6), new Arena(R.string.desc_arena_beira_rio, R.drawable.img_portoalegre));
		listDataChild.put(listDataHeader.get(7), new Arena(R.string.desc_arena_castelao, R.drawable.img_fortaleza));
		listDataChild.put(listDataHeader.get(8), new Arena(R.string.desc_arena_dunas, R.drawable.img_natal));
		listDataChild.put(listDataHeader.get(9), new Arena(R.string.desc_arena_maracana, R.drawable.img_rj));
		listDataChild.put(listDataHeader.get(10), new Arena(R.string.desc_arena_minerao, R.drawable.img_bh));
		listDataChild.put(listDataHeader.get(11), new Arena(R.string.desc_arena_nacional, R.drawable.img_bsb));
	}

}
