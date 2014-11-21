package br.com.epitrack.healthycup.util;

import java.util.List;

import android.content.Context;
import android.content.SharedPreferences;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.pm.PackageManager.NameNotFoundException;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.util.Log;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.Splash;
import br.com.epitrack.healthycup.DAO.UserDAO;
import br.com.epitrack.healthycup.classes.EventoCadastro;
import br.com.epitrack.healthycup.classes.EventoEsqueciSenha;
import br.com.epitrack.healthycup.classes.ItemCalendario;
import br.com.epitrack.healthycup.classes.Usuario;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

public class PreferenciasUtil {
	static final String TAG = "SaudeNaCopa";
	Context mContext;

	public PreferenciasUtil() {

	}

	public PreferenciasUtil(Context context) {
		mContext = context;
	}

	public boolean isOnline() {
		ConnectivityManager cm = (ConnectivityManager) mContext.getSystemService(Context.CONNECTIVITY_SERVICE);
		NetworkInfo netInfo = cm.getActiveNetworkInfo();
		if (netInfo != null && netInfo.isConnectedOrConnecting()) {
			return true;
		}
		return false;
	}

	public String getRegistrationId(Context context) {
		final SharedPreferences prefs = getGCMPreferences(context);
		String registrationId = prefs.getString(context.getString(R.string.id_key_reg_id), "");
		if (registrationId.isEmpty()) {
			Log.i(TAG, "Registration not found.");
			return "";
		}
		// Check if app was updated; if so, it must clear the registration ID
		// since the existing regID is not guaranteed to work with the new
		// app version.
		int registeredVersion = prefs.getInt(context.getString(R.string.id_key_app_verssion), Integer.MIN_VALUE);
		int currentVersion = getAppVersion(context);
		if (registeredVersion != currentVersion) {
			Log.i(TAG, "App version changed.");
			// atualizar o gcmid
			new AtualizarGCMID(registrationId, getUser(context)).execute();
			return "";
		}
		return registrationId;
	}

	private String getUser(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		return sharedPref.getString("userId", null);
	}

	/**
	 * @return Application's {@code SharedPreferences}.
	 */
	private SharedPreferences getGCMPreferences(Context context) {
		// This sample app persists the registration ID in shared preferences,
		// but
		// how you store the regID in your app is up to you.
		return context.getSharedPreferences(Splash.class.getSimpleName(), Context.MODE_PRIVATE);
	}

	/**
	 * @return Application's version code from the {@code PackageManager}.
	 */
	private static int getAppVersion(Context context) {
		try {
			PackageManager pm = context.getPackageManager();
			PackageInfo packageInfo = pm.getPackageInfo(context.getPackageName(), 0);
			return packageInfo.versionCode;
		} catch (NameNotFoundException e) {
			// should never happen
			throw new RuntimeException("Could not get package name: " + e);
		}
	}

	/**
	 * Stores the registration ID and app versionCode in the application's
	 * {@code SharedPreferences}.
	 * 
	 * @param context
	 *            application's context.
	 * @param regId
	 *            registration ID
	 */
	public void storeRegistrationId(Context context, String regId) {
		final SharedPreferences prefs = getGCMPreferences(context);
		int appVersion = getAppVersion(context);
		Log.i(TAG, "Saving regId on app version " + appVersion);
		SharedPreferences.Editor editor = prefs.edit();
		editor.putString(context.getString(R.string.id_key_reg_id), regId);
		editor.putInt(context.getString(R.string.id_key_app_verssion), appVersion);
		editor.commit();
	}

	/**
	 * Enviar Duvida
	 * 
	 * @author Guto
	 * 
	 */

	public class AtualizarGCMID extends AsyncTask<Void, Void, EventoEsqueciSenha> {

		private String gcmId;
		private String user;

		public AtualizarGCMID(String texto, String string) {
			this.gcmId = texto;
			this.user = string;
		}

		@Override
		protected EventoEsqueciSenha doInBackground(Void... arg0) {

			return new UserDAO().atualizaGCMID(gcmId, user);
		}

		protected void onPostExecute(EventoCadastro evento) {
			if (evento != null) {
				if (evento.getStatus()) {

				} else {

				}
			}

		}
	}

	public String getUserCompleto(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		return sharedPref.getString("user", null);
	}

	public void salvaUser(Usuario usuario, Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		Gson gson = new Gson();
		editor.putString("user", gson.toJson(usuario));
		editor.commit();

	}

	public void salvaArenaAtiva(int arenaAtiva, Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		// acrescento mais 1 para diferenciar do zero
		editor.putInt("arenaAtiva", arenaAtiva);
		editor.commit();

	}

	public int getArenaAtiva(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		return sharedPref.getInt("arenaAtiva", 0);
	}

	public void salvaNumeroArenas(int size, Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		editor.putInt("numArenas", size);
		editor.commit();

	}

	public int getNumeroArenas(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		return sharedPref.getInt("numArenas", 0);
	}

	public boolean isPrimeiraVez(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);
		if (!sharedPref.getBoolean("isPrimeiraVez", false)) {
			// salvar e retornar false
			SharedPreferences.Editor editor = sharedPref.edit();
			editor.putBoolean("isPrimeiraVez", true);
			editor.commit();

			return false;
		}

		return true;
	}

	public String getConsulados(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		return sharedPref.getString("embaixadas", null);
	}

	public void salvaConsulados(String result, Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		editor.putString("embaixadas", result);
		editor.commit();

	}

	public String getHospitais(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		return sharedPref.getString("hopitais", null);
	}

	public void salvaHospitais(String result, Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		editor.putString("hopitais", result);
		editor.commit();

	}

	public void salvaCalendario(List<ItemCalendario> itens, Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		Gson gson = new Gson();
		editor.putString("calendario", gson.toJson(itens, new TypeToken<List<ItemCalendario>>() {
		}.getType()));
		editor.commit();

	}

	public List<ItemCalendario> getCalendario(Context context) {
		SharedPreferences sharedPref = context.getSharedPreferences(context.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);
		Gson gson = new Gson();
		return gson.fromJson(sharedPref.getString("calendario", null), new TypeToken<List<ItemCalendario>>() {
		}.getType());
	}

}
