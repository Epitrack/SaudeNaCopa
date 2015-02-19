package br.com.epitrack.healthycup;

import java.io.IOException;
import java.util.Locale;
import java.util.concurrent.atomic.AtomicInteger;

import android.app.Activity;
import android.app.Fragment;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.StrictMode;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;
import br.com.epitrack.healthycup.DAO.UserDAO;
import br.com.epitrack.healthycup.classes.EventoCadastro;
import br.com.epitrack.healthycup.classes.EventoEsqueciSenha;
import br.com.epitrack.healthycup.classes.EventoLogin;
import br.com.epitrack.healthycup.classes.Usuario;
import br.com.epitrack.healthycup.util.PreferenciasUtil;
import br.com.epitrack.healthycup.util.Validator;

import com.google.analytics.tracking.android.EasyTracker;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.gcm.GoogleCloudMessaging;
import com.google.gson.Gson;

public class LoginActivity extends Activity {

	public static final String EXTRA_MESSAGE = "message";
	private final static int PLAY_SERVICES_RESOLUTION_REQUEST = 9000;

	String SENDER_ID = "559878614256";

	static final String TAG = "SaudeNaCopa";

	GoogleCloudMessaging gcm;
	AtomicInteger msgId = new AtomicInteger();
	SharedPreferences prefs;
	Context context;

	String regid;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		if (android.os.Build.VERSION.SDK_INT > 9) {
			StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
			StrictMode.setThreadPolicy(policy);
		}
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.activity_login);
		context = this.getApplicationContext();
		if (savedInstanceState == null) {
			// se nao tiver logado
			Fragment detailFragment = new OpcoesFragment();
			// Button
			// btnCadastrar=(Button)detailFragment.getView().findViewById(R.id.btnCadastrar);

			getFragmentManager().beginTransaction().add(R.id.layContainer, detailFragment).commit();
		}
		// Check device for Play Services APK. If check succeeds, proceed with
		// GCM registration.
		if (checkPlayServices()) {
			gcm = GoogleCloudMessaging.getInstance(this);
			regid = new PreferenciasUtil().getRegistrationId(context);

			if (regid.isEmpty()) {
				new RegisterInBackground().execute();
			}
		} else {
			Log.i(TAG, "No valid Google Play Services APK found.");
		}

	}
	
	 @Override
	  public void onStart() {
	    super.onStart();	    
	    EasyTracker.getInstance(this).activityStart(this);  
	  }

	  @Override
	  public void onStop() {
	    super.onStop();	    
	    EasyTracker.getInstance(this).activityStop(this);  
	  }

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.login, menu);
		return true;
	}

	/**
	 * Cadastrar usuario
	 * 
	 * @param v
	 */
	public void cadastrar(View v) {
		TextView edtApelido = (TextView) findViewById(R.id.edtApelido);
		TextView edtIdade = (TextView) findViewById(R.id.edtIdade);

		TextView txtEmail = (TextView) findViewById(R.id.edtEmail);
		TextView txtSenha = (TextView) findViewById(R.id.edtPass);

		RadioGroup radioButtonGroup = (RadioGroup) findViewById(R.id.radioSex);
		int radioButtonID = radioButtonGroup.getCheckedRadioButtonId();

		CheckBox ckBoxAcordo = (CheckBox) findViewById(R.id.checkBox1);

		// veririficar se preencheu tudo
		if (txtEmail.getText().length() == 0 || txtSenha.getText().length() == 0 || edtIdade.getText().length() == 0 || radioButtonID == -1) {

			Toast.makeText(this, getString(R.string.erro_preenchimento), Toast.LENGTH_SHORT).show();
			return;
		}
		// verifica se é maior de 12 anos
		if (Integer.parseInt(edtIdade.getText().toString()) <= 12) {
			Toast.makeText(this, getString(R.string.erro_menor12), Toast.LENGTH_SHORT).show();
			return;
		}
		// verifica se é menor de 120 anos
		if (Integer.parseInt(edtIdade.getText().toString()) > 120) {
			Toast.makeText(this, getString(R.string.erro_maior_99_anos), Toast.LENGTH_SHORT).show();
			return;
		}
		// verifica se conconda com os termos de uso
		if (!ckBoxAcordo.isChecked()) {
			Toast.makeText(this, getString(R.string.aceite_termo), Toast.LENGTH_SHORT).show();
			return;
		}

		// verificar se o email é válido
		String email_pattern = "^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@" + "[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$";
		if (!new Validator().validate(txtEmail.getText().toString(),email_pattern)) {
			Toast.makeText(this, getString(R.string.erro_preenchimento), Toast.LENGTH_SHORT).show();
			return;
		}
		
		//verificar se não digitou caracteres especiais em usuario
		String user_pattern = "^[A-Za-z0-9 ]*";
		if (!new Validator().validate(edtApelido.getText().toString(),user_pattern)) {
			Toast.makeText(this, getString(R.string.erro_preenchimento_caract_especial), Toast.LENGTH_SHORT).show();
			edtApelido.setText("");
			
			return;
		}
		


		// criar objeto usuario
		Usuario user = new Usuario();
		user.setNome(edtApelido.getText().toString());
		user.setIdade(Integer.parseInt(edtIdade.getText().toString()));
		// verificar se é maior de 12 anos

		RadioButton radioButton = (RadioButton) findViewById(radioButtonID);
		if (radioButton.getText() == getString(R.string.fem)) {
			user.setSexo("feminino");
		} else {
			user.setSexo("masculino");
		}

		user.setEmail(txtEmail.getText().toString());
		user.setSenha(txtSenha.getText().toString());

		// get o gcmid
		user.setGcmID(new PreferenciasUtil().getRegistrationId(this));

		// pega idioma
		String idioma = Locale.getDefault().getDisplayLanguage().substring(0, 3);
		String idIdioma = "2";
		if (idioma.equals("esp"))
			idIdioma = "1";
		if (idioma.equals("por"))
			idIdioma = "0";

		user.setIdioma(idIdioma);
		Log.i(TAG, user.getIdioma());

		// enviar requisicao para login
		new RealizaCadastro(user, this).execute();
		// desabiltar o botao
		Button btnEntrar = (Button) findViewById(R.id.btnCadastrar);
		btnEntrar.setText(getString(R.string.aguarde));
		btnEntrar.setEnabled(false);
	}

	/**
	 * Enviar senha
	 * 
	 * @param v
	 */
	public void enviarSenha(View v) {
		TextView txtEmail = (TextView) findViewById(R.id.txtLoginEmail);
		// veririficar se preencheu tudo
		if (txtEmail.getText().length() == 0) {
			Toast.makeText(this, getString(R.string.erro_preenchimento), Toast.LENGTH_SHORT).show();
			return;
		}
		new EsqueciSenha(txtEmail.getText().toString(), this).execute();
		Button btnEntrar = (Button) findViewById(R.id.btnLoginEntrar);
		btnEntrar.setText(getString(R.string.aguarde));
		btnEntrar.setEnabled(false);

	}

	/**
	 * entrar no app validar informacoes se ok ir para tela principal
	 * 
	 */
	public void entrar(View v) {
		TextView txtEmail = (TextView) findViewById(R.id.txtLoginEmail);
		TextView txtSenha = (TextView) findViewById(R.id.txtLoginSenha);
		// veririficar se preencheu tudo
		if (txtEmail.getText().length() == 0 || txtSenha.getText().length() == 0) {
			Toast.makeText(this, getString(R.string.erro_preenchimento), Toast.LENGTH_SHORT).show();
			return;
		}
		// enviar requisicao para login
		new RealizaLogin(txtEmail.getText().toString(), txtSenha.getText().toString(), this).execute();
		// desabiltar o botao
		Button btnEntrar = (Button) findViewById(R.id.btnLoginEntrar);
		btnEntrar.setText(getString(R.string.aguarde));
		btnEntrar.setEnabled(false);
	}

	public void showEntrar(View v) {

		Fragment detailFragment = new LoginFragment();
		showFragment(detailFragment);
	}

	public void showCadastre(View v) {
		Fragment detailFragment = new CadastroFragment();
		showFragment(detailFragment);

	}

	public void esqueceuSenha(View v) {
		Fragment detailFragment = new EsqueceuSenhaFragment();
		showFragment(detailFragment);

	}

	/**
	 * Abre dialo dos termos de uso
	 * 
	 */

	public void abreDialog(View v) {

		Intent intent = new Intent(this, TermosActivity.class);
		startActivity(intent);

		// LayoutInflater inflater = getLayoutInflater();
		// View dialoglayout = inflater.inflate(R.layout.webview_fragment,
		// null);
		//
		// WebView web = (WebView) dialoglayout.findViewById(R.id.webView1);
		// web.setWebViewClient(new myWebClient());
		// web.getSettings().setJavaScriptEnabled(true);
		// web.loadUrl("file:///android_asset/" + "termos_port.html");
		//
		// AlertDialog.Builder ad = new AlertDialog.Builder(this);
		// ad.setView(dialoglayout);
		//
		// ad.setPositiveButton(android.R.string.yes, new
		// android.content.DialogInterface.OnClickListener() {
		// public void onClick(DialogInterface dialog, int arg1) {
		//
		// }
		//
		// });
		// ad.show();

		// Fragment detailFragment = new TermosFragment();
		// // passa o argumento
		// Bundle args = new Bundle();
		// // TODO arrumar link correto
		// args.putString("pagina", "termos_port.html");
		//
		// detailFragment.setArguments(args);
		// getFragmentManager().beginTransaction().replace(R.id.layContainer,
		// detailFragment).addToBackStack("back").commit();

		// AlertDialog.Builder ad = new AlertDialog.Builder(this);
		// // ad.setIcon(R.drawable.icon);
		// ad.setTitle(getString(R.string.termos_uso));
		// ad.setView(LayoutInflater.from(this).inflate(R.layout.dialog_termos,
		// null));
		//
		// ad.setPositiveButton("OK", new
		// android.content.DialogInterface.OnClickListener() {
		// public void onClick(DialogInterface dialog, int arg1) {
		// // OK, go back to Main menu
		// }
		// });
		//
		// ad.setOnCancelListener(new DialogInterface.OnCancelListener() {
		// public void onCancel(DialogInterface dialog) {
		// // OK, go back to Main menu
		// }
		// });
		//
		// ad.show();

	}

	public class myWebClient extends WebViewClient {
		@Override
		public void onPageStarted(WebView view, String url, Bitmap favicon) {
			// TODO Auto-generated method stub
			super.onPageStarted(view, url, favicon);
		}

		@Override
		public boolean shouldOverrideUrlLoading(WebView view, String url) {
			// TODO Auto-generated method stub

			view.loadUrl(url);
			return true;

		}

		@Override
		public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
		}

		@Override
		public void onPageFinished(WebView view, String url) {
			// TODO Auto-generated method stub
			super.onPageFinished(view, url);

		}
	}

	public void showFragment(Fragment detailFragment) {

		getFragmentManager().beginTransaction().replace(R.id.layContainer, detailFragment).addToBackStack("back").commit();
	}

	/**
	 * Fragment opcoes
	 */
	public static class OpcoesFragment extends Fragment {
		public OpcoesFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.fragment_login_escolha, container, false);

			return rootView;
		}
	}

	/**
	 * Fragment cadastro
	 */
	public static class CadastroFragment extends Fragment {
		public CadastroFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.fragment_login_cadastro, container, false);

			return rootView;
		}
	}

	public static class TermosFragment extends Fragment {
		public TermosFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.webview_fragment, container, false);
			// busca a query
			Bundle args = getArguments();
			String html = args.getString("pagina");

			WebView web = (WebView) rootView.findViewById(R.id.webView1);
			web.setWebViewClient(new myWebClient());
			web.getSettings().setJavaScriptEnabled(true);
			web.loadUrl("file:///android_asset/" + html);
			return rootView;
		}

		public class myWebClient extends WebViewClient {
			@Override
			public void onPageStarted(WebView view, String url, Bitmap favicon) {
				// TODO Auto-generated method stub
				super.onPageStarted(view, url, favicon);
			}

			@Override
			public boolean shouldOverrideUrlLoading(WebView view, String url) {
				// TODO Auto-generated method stub

				view.loadUrl(url);
				return true;

			}

			@Override
			public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
			}

			@Override
			public void onPageFinished(WebView view, String url) {
				// TODO Auto-generated method stub
				super.onPageFinished(view, url);

			}
		}
	}

	/**
	 * Fragment login
	 */
	public static class LoginFragment extends Fragment {
		public LoginFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.fragment_login_entrar, container, false);

			return rootView;
		}
	}

	/**
	 * Fragment esqueceu senha
	 */
	public static class EsqueceuSenhaFragment extends Fragment {
		public EsqueceuSenhaFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.fragment_login_esqueceu_senha, container, false);

			return rootView;
		}
	}

	/**
	 * Realiza cadastro
	 * 
	 * @author Guto
	 * 
	 */

	public class RealizaCadastro extends AsyncTask<Void, Void, EventoCadastro> {

		private Usuario user;
		private Activity mActivity;

		public RealizaCadastro(Usuario user, Activity activity) {
			this.user = user;
			this.mActivity = activity;
		}

		@Override
		protected EventoCadastro doInBackground(Void... arg0) {

			return new UserDAO().realizaCadastro(user);
		}

		protected void onPostExecute(EventoCadastro evento) {
			if (evento != null) {
				// tratar dados do usuário
				if (evento.getStatus()) {
					// ir para o fragment login
					Toast.makeText(mActivity, getString(R.string.msg_cadastro_sucesso), Toast.LENGTH_SHORT).show();
					Fragment detailFragment = new LoginFragment();
					showFragment(detailFragment);
				} else {
					// erro no login
					Toast.makeText(mActivity, getString(R.string.erro_cadastro), Toast.LENGTH_SHORT).show();
				}
				Button btnEntrar = (Button) findViewById(R.id.btnCadastrar);
				btnEntrar.setText(getString(R.string.cadastrese));
				btnEntrar.setEnabled(true);
			}
		}

	}

	/**
	 * Login
	 * 
	 * @author Guto
	 * 
	 */
	public class RealizaLogin extends AsyncTask<Void, Void, EventoLogin> {

		private String email;
		private String senha;
		private Activity mActivity;

		public RealizaLogin(String email, String senha, Activity activity) {
			this.email = email;
			this.senha = senha;
			this.mActivity = activity;

		}

		@Override
		protected EventoLogin doInBackground(Void... arg0) {

			return new UserDAO().realizaLogin(email, senha);
		}

		protected void onPostExecute(EventoLogin evento) {
			if (evento != null) {
				// tratar dados do usuário
				if (evento.getStatus()) {

					// registrar que está logado
					registraLogin(email, senha, evento.getUsuario().getUserID());

					// passar os dadoas para activity
					Intent intent = new Intent(mActivity, SentimentoActivity.class);
					Gson gson = new Gson();
					intent.putExtra("usuario", gson.toJson(evento.getUsuario()));
					startActivity(intent);
				} else {
					// erro no login
					Toast.makeText(mActivity, getString(R.string.erro_login), Toast.LENGTH_SHORT).show();
					Button btnEntrar = (Button) findViewById(R.id.btnLoginEntrar);
					btnEntrar.setText(getString(R.string.entrar));
					btnEntrar.setEnabled(true);
				}

			}
		}

	}

	/**
	 * Esqueci senha
	 * 
	 * @author Guto
	 * 
	 */

	public class EsqueciSenha extends AsyncTask<Void, Void, EventoEsqueciSenha> {

		private String email;
		private Activity mActivity;

		public EsqueciSenha(String email, Activity activity) {
			this.email = email;
			this.mActivity = activity;
		}

		@Override
		protected EventoEsqueciSenha doInBackground(Void... arg0) {

			return new UserDAO().esqueciSenha(email);
		}

		protected void onPostExecute(EventoEsqueciSenha evento) {
			if (evento != null) {
				// tratar dados do usuário
				if (evento.getStatus()) {
					// passar os dadoas para activity
					Toast.makeText(mActivity, getString(R.string.envio_email_sucesso), Toast.LENGTH_SHORT).show();
					Fragment detailFragment = new LoginFragment();
					showFragment(detailFragment);
				} else {
					// erro no envio de email
					Toast.makeText(mActivity, getString(R.string.erro_envio_email), Toast.LENGTH_SHORT).show();
				}
				Button btnEntrar = (Button) findViewById(R.id.btnLoginEntrar);
				btnEntrar.setText(getString(R.string.enviar));
				btnEntrar.setEnabled(true);
			}
		}

	}

	public void registraLogin(String email, String senha, String userId) {

		SharedPreferences sharedPref = getSharedPreferences(getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		editor.putString("email", email);
		editor.putString("senha", senha);
		editor.putString("userId", userId);
		editor.commit();
		new PreferenciasUtil().getRegistrationId(this);

	}

	private boolean checkPlayServices() {
		int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(this);
		if (resultCode != ConnectionResult.SUCCESS) {
			if (GooglePlayServicesUtil.isUserRecoverableError(resultCode)) {
				GooglePlayServicesUtil.getErrorDialog(resultCode, this, PLAY_SERVICES_RESOLUTION_REQUEST).show();
			} else {
				Log.i(TAG, "This device is not supported.");
				finish();
			}
			return false;
		}
		return true;
	}

	/**
	 * Registers the application with GCM servers asynchronously.
	 * <p>
	 * Stores the registration ID and app versionCode in the application's
	 * shared preferences.
	 */
	public class RegisterInBackground extends AsyncTask<Void, Void, String> {

		public RegisterInBackground() {
		}

		@Override
		protected String doInBackground(Void... arg0) {
			String msg = "";
			try {
				if (gcm == null) {
					gcm = GoogleCloudMessaging.getInstance(context);
				}
				regid = gcm.register(SENDER_ID);
				msg = "Device registered, registration ID=" + regid;

				// You should send the registration ID to your server over HTTP,
				// so it can use GCM/HTTP or CCS to send messages to your app.
				// The request to your server should be authenticated if your
				// app
				// is using accounts.
				sendRegistrationIdToBackend();

				// For this demo: we don't need to send it because the device
				// will send upstream messages to a server that echo back the
				// message using the 'from' address in the message.

				// Persist the regID - no need to register again.
				new PreferenciasUtil().storeRegistrationId(context, regid);
			} catch (IOException ex) {
				msg = "Error :" + ex.getMessage();
				// If there is an error, don't just keep trying to register.
				// Require the user to click a button again, or perform
				// exponential back-off.
			}
			return msg;
		}

	}

	private void sendRegistrationIdToBackend() {
		// TODO Your implementation here.
		Log.i(TAG, "regId: " + regid);
	}

}
