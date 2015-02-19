package br.com.epitrack.healthycup;

import java.util.Locale;

import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.app.ActionBarActivity;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import br.com.epitrack.healthycup.fragments.maisopcoes.SobreTermosFragment;

public class TermosActivity extends ActionBarActivity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.activity_termos);

		SobreTermosFragment detailFragment = new SobreTermosFragment();
		// passa o argumento
		Bundle args = new Bundle();

		args.putString("pagina", "termos");
		detailFragment.setArguments(args);

		if (savedInstanceState == null) {
			getSupportFragmentManager().beginTransaction().add(R.id.container, detailFragment).commit();
		}
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {

		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.termos, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle action bar item clicks here. The action bar will
		// automatically handle clicks on the Home/Up button, so long
		// as you specify a parent activity in AndroidManifest.xml.
		int id = item.getItemId();
		if (id == R.id.action_settings) {
			return true;
		}
		return super.onOptionsItemSelected(item);
	}

	/**
	 * A placeholder fragment containing a simple view.
	 */
	public static class PlaceholderFragment extends Fragment {

		public PlaceholderFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.webview_fragment, container, false);
			// busca a query
			// Bundle args = getArguments();
			// String html = args.getString("pagina");

			WebView web = (WebView) rootView.findViewById(R.id.webView1);
			// web.setWebViewClient();
			web.getSettings().setJavaScriptEnabled(true);
			// verificar o idioma para popular o termos certo
			String idioma = Locale.getDefault().getDisplayLanguage().substring(0, 3);
			web.loadUrl("file:///android_asset/termos_" + idioma.toLowerCase() + ".html");
			return rootView;
		}
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
