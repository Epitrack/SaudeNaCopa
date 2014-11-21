package br.com.epitrack.healthycup.fragments.saude;

import java.util.ArrayList;
import java.util.List;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.adapters.TelefonesAdapter;
import br.com.epitrack.healthycup.classes.Telefone;

public class TelefonesFragment extends Fragment {

	public TelefonesFragment() {

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
			view = inflater.inflate(R.layout.saude_telefones_fragment, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}

		final List<Telefone> telefones = new ArrayList<Telefone>();
		telefones.add(new Telefone(getString(R.string.samu), "192"));
		telefones.add(new Telefone(getString(R.string.bombeiros), "193"));
		telefones.add(new Telefone(getString(R.string.defesa_civil), "0800 644 0199"));
		telefones.add(new Telefone(getString(R.string.pm), "190"));
		telefones.add(new Telefone(getString(R.string.pc), "197"));
		telefones.add(new Telefone(getString(R.string.prf), "191"));
		telefones.add(new Telefone(getString(R.string.pf), "194"));
		telefones.add(new Telefone(getString(R.string.disque_intox), "0800 722 6001"));
		telefones.add(new Telefone(getString(R.string.abuso_infantil), "100"));
		telefones.add(new Telefone(getString(R.string.disquesaude), "136"));
		telefones.add(new Telefone(getString(R.string.vigiagro), "0800 704 1995"));
		telefones.add(new Telefone(getString(R.string.anac), "0800 725 4445"));
		telefones.add(new Telefone(getString(R.string.antt), "166"));
		telefones.add(new Telefone(getString(R.string.antaq), "0800 644 500"));
		telefones.add(new Telefone(getString(R.string.anvisa), "0800 642 9782"));
		telefones.add(new Telefone(getString(R.string.cievs), "0800 644 6645"));
		telefones.add(new Telefone(getString(R.string.cve), "0800 555 466"));

		TelefonesAdapter adapter = new TelefonesAdapter(getActivity(), telefones);
		ListView lvTel = (ListView) view.findViewById(R.id.listView1);
		lvTel.setAdapter(adapter);
		lvTel.setOnItemClickListener(new OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2, long arg3) {
				// Intent i = new
				// Intent(ClassName.this,CourtDetailActivity.class);
				// startActivity(i);
				;

				String phoneCallUri = "tel:" + telefones.get(arg2).getNumero();
				Intent phoneCallIntent = new Intent(Intent.ACTION_DIAL);
				phoneCallIntent.setData(Uri.parse(phoneCallUri));
				startActivity(phoneCallIntent);
			};
		});
		// ImageView imgVoltar = (ImageView) view.findViewById(R.id.imgVoltar);
		// imgVoltar.setVisibility(View.VISIBLE);

		return view;
	}

}
