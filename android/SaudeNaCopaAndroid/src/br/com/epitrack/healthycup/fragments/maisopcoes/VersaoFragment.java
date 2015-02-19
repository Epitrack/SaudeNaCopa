package br.com.epitrack.healthycup.fragments.maisopcoes;

import java.util.HashMap;
import java.util.List;

import android.content.pm.PackageManager.NameNotFoundException;
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

public class VersaoFragment extends Fragment {

	public VersaoFragment() {

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
			view = inflater.inflate(R.layout.fragment_versao, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}

		// atualiza versao
		try {
			String versionName = getActivity().getPackageManager().getPackageInfo(getActivity().getPackageName(), 0).versionName;
			TextView lblVersao = (TextView) view.findViewById(R.id.lblVersao);
			lblVersao.setText(getActivity().getString(R.string.versao) + " " + versionName);

		} catch (NameNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		return view;
	}

}
