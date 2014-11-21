package br.com.epitrack.healthycup.fragments.maisopcoes;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import br.com.epitrack.healthycup.R;

public class ItensJogo extends Fragment {

	public ItensJogo() {

	}

	private static View view;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		if (view != null) {
			ViewGroup parent = (ViewGroup) view.getParent();
			if (parent != null)
				parent.removeView(view);
		}
		try {
			view = inflater.inflate(R.layout.fragment_informacoes_itens_jogos, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}

		return view;
	}
}
