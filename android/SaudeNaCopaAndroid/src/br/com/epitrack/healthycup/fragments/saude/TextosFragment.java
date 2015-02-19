package br.com.epitrack.healthycup.fragments.saude;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Html;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import br.com.epitrack.healthycup.R;

public class TextosFragment extends Fragment {

	public TextosFragment() {

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
			view = inflater.inflate(R.layout.texto_fragment, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}

		// busca a texto
		Bundle args = getArguments();
		int textoId = args.getInt("texto");

		TextView txtLembrete = (TextView) view.findViewById(R.id.textView1);
		txtLembrete.setText(Html.fromHtml(getString(textoId)));

		return view;
	}
}