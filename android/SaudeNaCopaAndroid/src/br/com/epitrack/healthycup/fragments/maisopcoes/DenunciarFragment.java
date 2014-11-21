package br.com.epitrack.healthycup.fragments.maisopcoes;

import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.DAO.UserDAO;
import br.com.epitrack.healthycup.classes.EventoCadastro;
import br.com.epitrack.healthycup.classes.Usuario;

import com.google.gson.Gson;

public class DenunciarFragment extends Fragment {

	public DenunciarFragment() {

	}

	private static View view;
	private static Usuario user;
	private EditText texto;

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

		// busca a query
		Bundle args = getArguments();
		Gson gson = new Gson();
		user = gson.fromJson(args.getString("user"), Usuario.class);

		texto = (EditText) view.findViewById(R.id.editText1);
		Button btnEnviar = (Button) view.findViewById(R.id.button1);
		btnEnviar.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				new EnviarDuvida(texto.getText().toString(), user).execute();

			}
		});

		return view;
	}

	/**
	 * Enviar Duvida
	 * 
	 * @author Guto
	 * 
	 */

	public class EnviarDuvida extends AsyncTask<Void, Void, EventoCadastro> {

		private String duvida;
		private Usuario user;

		public EnviarDuvida(String texto, Usuario user) {
			this.duvida = texto;
			this.user = user;
		}

		@Override
		protected EventoCadastro doInBackground(Void... arg0) {

			return new UserDAO().enviaDuvida(duvida, user);
		}

		protected void onPostExecute(EventoCadastro evento) {
			if (evento != null) {
				if (evento.getStatus()) {
					Toast.makeText(view.getContext(), getString(R.string.envio_duvida_sucesso), Toast.LENGTH_LONG).show();
					texto.setText("");
				} else {
					// erro no envio de email
					Toast.makeText(view.getContext(), getString(R.string.envio_duvida_erro), Toast.LENGTH_LONG).show();
				}
			}

		}
	}
}