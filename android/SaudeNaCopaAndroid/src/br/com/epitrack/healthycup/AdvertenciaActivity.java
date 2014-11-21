package br.com.epitrack.healthycup;

import android.app.Activity;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.ActionBarActivity;
import android.view.Menu;
import android.view.View;
import android.view.Window;

public class AdvertenciaActivity extends Activity implements Runnable {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.activity_advertencia);
		Handler handler = new Handler();
		handler.postDelayed(this, 8000);
		
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {

		// Inflate the menu; this adds items to the action bar if it is present.
		//getMenuInflater().inflate(R.menu.advertencia, menu);
		return true;
	}

	@Override
	public void run() {
		finish();
		
	}
	
	public void fechar(View v) {
		finish();
	}




}
