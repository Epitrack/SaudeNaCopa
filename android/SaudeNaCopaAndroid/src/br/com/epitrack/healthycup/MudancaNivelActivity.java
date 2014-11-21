package br.com.epitrack.healthycup;

import android.app.Activity;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.view.Window;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import br.com.epitrack.healthycup.util.SystemUiHider;

/**
 * An example full-screen activity that shows and hides the system UI (i.e. status bar and navigation/system bar) with user interaction.
 * 
 * @see SystemUiHider
 */
public class MudancaNivelActivity extends Activity implements Runnable {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);

		setContentView(R.layout.activity_mudanca_nivel);
		ImageView imgCat = (ImageView) findViewById(R.id.imageView1);
		ImageView imgTrofeu = (ImageView) findViewById(R.id.imageView2);
		TextView mensagem = (TextView) findViewById(R.id.txtTwitterNomeApresentacao);
		RelativeLayout rl = (RelativeLayout) findViewById(R.id.rlMudancaNivel);
		TextView txtNomeArena = (TextView) findViewById(R.id.txtNomeArena);

		Bundle b = getIntent().getExtras();
		if (b.getInt("background", 0) > 0 && b.getInt("imagemCategoria", 0) > 0) {
			imgCat.setBackgroundResource(b.getInt("imagemCategoria"));
			imgTrofeu.setBackgroundResource(b.getInt("imagemTrofeu"));
			rl.setBackgroundResource(b.getInt("background"));
			txtNomeArena.setText(getString(b.getInt("nomeArena")));
			mensagem.setText(getString(R.string.parabens_novo_estadio_arena));
			mensagem.setTextColor(getResources().getColor(R.color.white));
		} else {
			if (b.getInt("background", 0) == 0) {
				// se nao tem background é pq é mudança de nível
				imgCat.setBackgroundResource(b.getInt("imagemCategoria"));
				imgTrofeu.setBackgroundResource(b.getInt("imagemTrofeu"));
				mensagem.setText(getString(R.string.parabens_categoria));
				mensagem.setTextColor(getResources().getColor(R.color.azul_texto));
			} else {
				// senao é novo estdio disponível
				rl.setBackgroundResource(b.getInt("background"));
				mensagem.setText(getString(R.string.parabens_novo_estadio));
				txtNomeArena.setText(getString(b.getInt("nomeArena")));
				imgCat.setVisibility(View.INVISIBLE);
				imgTrofeu.setVisibility(View.INVISIBLE);
				mensagem.setTextColor(getResources().getColor(R.color.white));
			}
		}
		Handler handler = new Handler();
		handler.postDelayed(this, 4000);

	}

	@Override
	public void run() {
		finish();

	}

	public void fechar(View v) {
		finish();
	}

}
