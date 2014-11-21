package br.com.epitrack.healthycup;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.ActionBarActivity;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.TextView;

public class InformacoesActivity extends ActionBarActivity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.activity_informacoes);

		if (savedInstanceState == null) {

			FragmentTransaction fragmentTransaction;
			Fragment fragment = new PlaceholderFragment();
			Bundle arg = new Bundle();
			arg.putString("titulo", getString(R.string.informacoes));
			arg.putInt("fragment", R.layout.fragment_informacoes);
			fragment.setArguments(arg);

			fragmentTransaction = getSupportFragmentManager().beginTransaction();
			fragmentTransaction.add(R.id.container, fragment);
			fragmentTransaction.addToBackStack(null);
			fragmentTransaction.commit();
			
		}
	}

	public void sair(View v) {
		finish();
	}

	public void abreFragment(View v) {
		int id = v.getId();

		Bundle arg = new Bundle();
		Fragment fragment = new PlaceholderFragment();

		switch (id) {
		case R.id.btnItensJogo:
			arg.putString("titulo", getString(R.string.itens_jogo));
			arg.putInt("fragment", R.layout.fragment_informacoes_itens_jogos);
			fragment.setArguments(arg);
			getSupportFragmentManager().beginTransaction().replace(R.id.container, fragment).commit();
			break;

		default:
			break;
		}

	}

	/**
	 * A placeholder fragment containing a simple view.
	 */
	public static class PlaceholderFragment extends Fragment {
		int fragment;
		String titulo;

		public PlaceholderFragment() {
		}
		private static View view;

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			Bundle arg = getArguments();
			titulo = arg.getString("titulo");
			fragment = arg.getInt("fragment");
			if (view != null) {
				ViewGroup parent = (ViewGroup) view.getParent();
				if (parent != null)
					parent.removeView(view);
			}
			try {
				view = inflater.inflate(fragment, container, false);
			} catch (InflateException e) {
				/* map is already there, just return view as it is */
			}

			
			View rootView = inflater.inflate(fragment, container, false);
//			TextView txtTitulo =(TextView) container.findViewById(R.id.txtTitulo);
//			txtTitulo.setText(titulo);
			return rootView;
		}
	}

}
