package br.com.epitrack.healthycup.fragments.maisopcoes;

import android.content.Context;
import android.content.SharedPreferences;
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
import br.com.epitrack.healthycup.util.PreferenciasUtil;

import com.google.gson.Gson;

public class AlterarSenhaFragment extends Fragment {

	public AlterarSenhaFragment() {

	}

	private static View view;
	private static Usuario user;
	private EditText senhaAntiga;
	private EditText senhaNova;
	private EditText senhaConfirmacao;
	Button btnEnviar;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		if (view != null) {
			ViewGroup parent = (ViewGroup) view.getParent();
			if (parent != null)
				parent.removeView(view);
		}
		try {
			view = inflater.inflate(R.layout.altera_senha_fragment, container, false);
		} catch (InflateException e) {
			
		}

		senhaAntiga = (EditText) view.findViewById(R.id.editText1);
		senhaNova = (EditText) view.findViewById(R.id.editText2);
		senhaConfirmacao = (EditText) view.findViewById(R.id.editText3);
		btnEnviar = (Button) view.findViewById(R.id.button1);
		btnEnviar.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				// verificar se a senha antiga esta ok
				SharedPreferences sharedPref = getActivity().getSharedPreferences(getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);
				String senha = sharedPref.getString("senha", null);
				if (!senha.equals(senhaAntiga.getText().toString())) {
					// mostrar erro
					Toast.makeText(view.getContext(), getString(R.string.erro_senha_nao_confere), Toast.LENGTH_SHORT).show();
					return;
				}
				if (senhaNova.getText().toString().length() == 0 || !senhaNova.getText().toString().equals(senhaConfirmacao.getText().toString())) {
					// erro senhas não coferem
					Toast.makeText(view.getContext(), getString(R.string.erro_senhas_nao_confere), Toast.LENGTH_SHORT).show();
					return;
				}
				Gson gson = new Gson();
				user = gson.fromJson(new PreferenciasUtil().getUserCompleto(getActivity().getBaseContext()), Usuario.class);

				new TrocarSenha(senhaAntiga.getText().toString(), senhaNova.getText().toString(), user).execute();
				
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

	public class TrocarSenha extends AsyncTask<Void, Void, EventoCadastro> {

		private String senha;
		private String senhaAtual;
		private Usuario user;

		public TrocarSenha(String senhaAtual, String texto, Usuario user) {
			this.senha = texto;
			this.user = user;
			this.senhaAtual = senhaAtual;
		}

		@Override
		protected EventoCadastro doInBackground(Void... arg0) {
			btnEnviar.setEnabled(false);
			return new UserDAO().trocaSenha(senhaAtual, senha, user);
		}

		protected void onPostExecute(EventoCadastro evento) {
			if (evento != null) {
				if (evento.getStatus()) {
					Toast.makeText(view.getContext(), getString(R.string.senha_trocada_sucesso), Toast.LENGTH_LONG).show();
					
					senhaAntiga.setText("");
					senhaConfirmacao.setText("");
					senhaNova.setText("");
					
				} else {
					// erro na troca da senha
					Toast.makeText(view.getContext(), getString(R.string.senha_trocada_erro), Toast.LENGTH_LONG).show();
				}
			}
			btnEnviar.setEnabled(true);

		}
	}
}