package br.com.epitrack.healthycup.fragments.maisopcoes;

import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import br.com.epitrack.healthycup.R;

public class WebviewFragment extends Fragment {

	public WebviewFragment() {

	}

	private static View view;

	WebView web;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		if (view != null) {
			ViewGroup parent = (ViewGroup) view.getParent();
			if (parent != null)
				parent.removeView(view);
		}
		try {
			view = inflater.inflate(R.layout.webview_fragment, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}

		// busca a query
		Bundle args = getArguments();
		String html = args.getString("pagina");

		web = (WebView) view.findViewById(R.id.webView1);
		web.setWebViewClient(new myWebClient());
		web.getSettings().setJavaScriptEnabled(true);
		web.loadUrl("file:///android_asset/" + html);
		return view;
	}

	public class myWebClient extends WebViewClient {
		@Override
		public void onPageStarted(WebView view, String url, Bitmap favicon) {
			// TODO Auto-generated method stub
			super.onPageStarted(view, url, favicon);
		}

		@Override
		public boolean shouldOverrideUrlLoading(WebView view, String url) {

			try {
				//redirecionar para o fragment de links
				//atualizaTitulo(R.string.linksuteis, R.color.azul_saiba_mais);
//				new SentimentoActivity().abreLinkUteis(view);
				LinksFragment detailFragment = new LinksFragment();
				getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
				atualizaTitulo(R.string.linksuteis, R.color.azul_saiba_mais);
				ImageView btn = (ImageView) getActivity().findViewById(R.id.menu_item_turismo);
				resetaBotoes();
				
//				Intent intent = new Intent(view.getContext(), SentimentoActivity.class);
//				intent.putExtra("links", true);
//				startActivity(intent);
				
			} catch (Exception e) {
//				Logger.log(e);
			}

			return true;

		}

		private void resetaBotoes() {

			ImageView img = (ImageView) getActivity().findViewById(R.id.menu_item_saude);
			img.setImageResource(R.drawable.icone_saude_off);

			img = (ImageView) getActivity().findViewById(R.id.menu_item_turismo);
			img.setImageResource(R.drawable.ico_mais_on);

			
			
		}

		private void atualizaTitulo(int titulo, int cor) {
			RelativeLayout rl = (RelativeLayout) getActivity().findViewById(R.id.llTopo);
			rl.setVisibility(View.VISIBLE);
			rl.setBackgroundResource(cor);
			TextView text = (TextView) getActivity().findViewById(R.id.barra_titulo);
			text.setText(getString(titulo).toUpperCase());
			text.setBackgroundResource(cor);
			
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