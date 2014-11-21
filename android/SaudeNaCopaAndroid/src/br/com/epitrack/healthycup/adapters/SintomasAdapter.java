package br.com.epitrack.healthycup.adapters;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.TextView;
import br.com.epitrack.healthycup.R;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

public class SintomasAdapter extends BaseAdapter {
	private static final String TAG = "SaudeNaCopa";

	private Activity activity;
	private String[] sintomas;
	private Map<String, Boolean> sintomasCheck;

	public SintomasAdapter(Activity activity2, String[] sintomas2) {
		//
		this.sintomas = sintomas2;
		this.activity = activity2;
		sintomasCheck = new HashMap<String, Boolean>();
		for (int i = 0; i < sintomas2.length; i++) {
			sintomasCheck.put(sintomas2[i], false);
		}
	}

	@Override
	public int getCount() {
		return sintomas.length;
	}

	@Override
	public Object getItem(int position) {
		return null;
	}

	// Will get called to provide the ID that
	// is passed to OnItemClickListener.onItemClick()
	// @Override
	// public long getItemId(int position) {
	// return mThumbIds.get(position);
	// }

	// create a new ImageView for each item referenced by the Adapter
	@Override
	public View getView(final int position, View convertView, ViewGroup parent) {
		// ImageView imageView = (ImageView) convertView;
		ViewHolder holder = null;
		LayoutInflater inflator = activity.getLayoutInflater();
		// if convertView's not recycled, initialize some attributes
		if (convertView == null) {
			holder = new ViewHolder();
			convertView = inflator.inflate(R.layout.item_adapter_sintoma, null);
			holder.sintoma = (TextView) convertView.findViewById(R.id.txtTwitterNomeApresentacao);
			holder.check = (CheckBox) convertView.findViewById(R.id.checkBox1);

			holder.check.setTag(holder);
			holder.check.setOnClickListener(new OnClickListener() {
				public void onClick(View v) {
					ViewHolder vh = (ViewHolder) v.getTag();
					CheckBox ckb = (CheckBox) v.findViewById(R.id.checkBox1);
					salvaSintomaSelecionado(vh.sintoma.getText().toString(), ckb.isChecked());
					sintomasCheck.put(vh.sintoma.getText().toString(), ckb.isChecked());
					Log.i(TAG, String.valueOf(position) + ":" + String.valueOf(ckb.isChecked()));

				}
			});

			convertView.setTag(holder);

		} else {
			holder = (ViewHolder) convertView.getTag();
		}

		holder.sintoma.setText(sintomas[position]);
		holder.position = position;

		holder.check.setChecked(sintomasCheck.get(sintomas[position]));

		return convertView;

	}

	protected void salvaSintomaSelecionado(String textoSintoma, boolean isChecked) {
		Gson gson = new Gson();

		SharedPreferences sharedPref = activity.getSharedPreferences(activity.getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);
		List<Boolean> sentimentosSelecionados = gson.fromJson(sharedPref.getString(activity.getString(R.string.id_key_sentimentos), null),
				new TypeToken<List<Boolean>>() {
				}.getType());
		// salva no sharedpreferences
		if (sentimentosSelecionados == null) {
			sentimentosSelecionados = new ArrayList<Boolean>();
			for (int i = 0; i < 12; i++) {
				sentimentosSelecionados.add(false);
			}
		}
		//procura qual posicao é o sentimento passado
		int pos=0;
		for (int i = 0; i < sintomas.length; i++) {
			if(sintomas[i].equals(textoSintoma)){
				pos = i;
			}
		}
		sentimentosSelecionados.set(pos, isChecked);

		SharedPreferences.Editor editor = sharedPref.edit();
		editor.putString(activity.getString(R.string.id_key_sentimentos), gson.toJson(sentimentosSelecionados));
		editor.commit();

	}

	private class ViewHolder {
		// The position of this row in list
		@SuppressWarnings("unused")
		private int position;

		// The image view for each row
		private TextView sintoma;
		private CheckBox check;

	}

	@Override
	public long getItemId(int arg0) {
		// Auto-generated method stub
		return 0;
	}

}
