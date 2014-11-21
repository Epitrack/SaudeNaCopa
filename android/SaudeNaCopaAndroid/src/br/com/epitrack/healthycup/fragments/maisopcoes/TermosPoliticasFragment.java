package br.com.epitrack.healthycup.fragments.maisopcoes;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import br.com.epitrack.healthycup.R;

public class TermosPoliticasFragment extends Fragment {

	public TermosPoliticasFragment() {

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
			view = inflater.inflate(R.layout.denunciar_fragment, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}

		// TextView text = (TextView) view.findViewById(R.id.barra_titulo);
		// text.setText(getString(R.string.lembretes_saude));

		EditText texto = (EditText) view.findViewById(R.id.editText1);
		Button btnEnviar =  (Button) view.findViewById(R.id.button1);
		
		return view;
	}
}