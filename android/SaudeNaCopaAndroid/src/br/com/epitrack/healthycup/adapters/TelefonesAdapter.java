package br.com.epitrack.healthycup.adapters;

import java.util.List;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Telefone;

public class TelefonesAdapter extends BaseAdapter {

	private Activity activity;
	private List<Telefone> telefones;

	public TelefonesAdapter(Activity activity2, List<Telefone> telefones) {
		//
		this.telefones = telefones;
		this.activity = activity2;
	}

	@Override
	public int getCount() {
		return telefones.size();
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
			convertView = inflator.inflate(R.layout.item_telefone, null);
			holder.telefone = (TextView) convertView.findViewById(R.id.txtTwitterNomeApresentacao);
			holder.numero = (TextView) convertView.findViewById(R.id.txtNumTel);

			convertView.setTag(holder);

		} else {
			holder = (ViewHolder) convertView.getTag();
		}

		holder.telefone.setText(telefones.get(position).getNome());
		holder.numero.setText(telefones.get(position).getNumero());
		holder.position = position;

		return convertView;

	}

	private class ViewHolder {
		// The position of this row in list
		@SuppressWarnings("unused")
		private int position;

		// The image view for each row
		private TextView telefone;
		private TextView numero;

	}

	@Override
	public long getItemId(int arg0) {
		// Auto-generated method stub
		return 0;
	}

}
