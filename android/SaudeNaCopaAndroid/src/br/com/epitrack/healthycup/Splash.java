package br.com.epitrack.healthycup;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.widget.RelativeLayout;
import android.widget.Toast;
import br.com.epitrack.healthycup.DAO.UserDAO;
import br.com.epitrack.healthycup.classes.EventoLogin;
import br.com.epitrack.healthycup.classes.Usuario;
import br.com.epitrack.healthycup.util.PreferenciasUtil;

import com.google.gson.Gson;

public class Splash extends Activity implements Runnable {
	private Activity mActivity;
	public static final String EXTRA_MESSAGE = "message";

	static final String TAG = "SaudeNaCopa";

	SharedPreferences prefs;
	Context context;

	String regid;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_splash);

		mActivity = this;

		// verifica se tem internet disponivel
		PreferenciasUtil pu = new PreferenciasUtil(this);
		Gson gson = new Gson();
		Usuario user = gson.fromJson(pu.getUserCompleto(this), Usuario.class);
		if (!pu.isOnline() && user != null) {
			Handler handler = new Handler();
			handler.postDelayed(this, 4000);

		} else {
			// verifica se está logado

			SharedPreferences sharedPref = getSharedPreferences(getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);
			if (sharedPref.getString("email", null) != null) {
				new RealizaLogin(sharedPref.getString("email", null), sharedPref.getString("senha", null), this).execute();

			} else {

				Handler handler = new Handler();
				handler.postDelayed(this, 4000);

			}
		}

	}

	public void avancar(View v) {
		startActivity(new Intent(this, LoginActivity.class));
		finish();
	}

	@Override
	public void run() {
		PreferenciasUtil pu = new PreferenciasUtil(this);
		Gson gson = new Gson();
		Usuario user = gson.fromJson(pu.getUserCompleto(this), Usuario.class);
		if (!pu.isOnline() && user != null) {
			Intent intent = new Intent(mActivity, SentimentoActivity.class);
			intent.putExtra("usuario", gson.toJson(user));
			startActivity(intent);
			finish();
		} else {
			// veririfca se é a primeira vez que abriu o app
			// se for, abrir tela de apresentacao
			if (!new PreferenciasUtil().isPrimeiraVez(getApplicationContext())) {
				// mostrar tela de apresentação
				RelativeLayout rlSplash1 = (RelativeLayout) findViewById(R.id.rlSplash1);
				rlSplash1.setVisibility(View.INVISIBLE);
				RelativeLayout rlSplash2 = (RelativeLayout) findViewById(R.id.rlSplash2);
				rlSplash2.setVisibility(View.VISIBLE);

			} else {
				startActivity(new Intent(this, LoginActivity.class));
				finish();
			}
		}

	}

	/**
	 * Buscar Localizacao
	 * 
	 * @author Guto
	 * 
	 */
	//
	// public class BuscarLocalizacao extends AsyncTask<Void, Void, Void> {
	//
	// private Activity mActivity;
	//
	// public BuscarLocalizacao(Activity activity) {
	// this.mActivity = activity;
	// }
	//
	// @Override
	// protected Void doInBackground(Void... arg0) {
	// // new UserDAO().atualizaLocalizacao(mActivity);
	// return null;
	// }
	//
	// }

	public class RealizaLogin extends AsyncTask<Void, Void, EventoLogin> {

		private String email;
		private String senha;
		private Activity mActivity;

		public RealizaLogin(String email, String senha, Activity activity) {
			this.email = email;
			this.senha = senha;
			this.mActivity = activity;
			new PreferenciasUtil().getRegistrationId(activity);
		}

		@Override
		protected EventoLogin doInBackground(Void... arg0) {

			return new UserDAO().realizaLogin(email, senha);
		}

		protected void onPostExecute(EventoLogin evento) {
			if (evento != null) {
				// tratar dados do usuário
				if (evento.getStatus()) {
					// salva o usuario em preferencias
					new PreferenciasUtil(mActivity).salvaUser(evento.getUsuario(), mActivity);

					// passar os dados para activity
					Intent intent = new Intent(mActivity, SentimentoActivity.class);
					Gson gson = new Gson();
					intent.putExtra("usuario", gson.toJson(evento.getUsuario()));
					startActivity(intent);
					finish();
				} else {
					// erro no login
					Toast.makeText(mActivity, getString(R.string.erro_login), Toast.LENGTH_SHORT).show();
					startActivity(new Intent(mActivity, LoginActivity.class));
					finish();
				}
			}
		}

	}

}
