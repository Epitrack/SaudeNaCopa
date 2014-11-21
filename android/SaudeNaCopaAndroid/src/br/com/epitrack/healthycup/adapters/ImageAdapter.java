package br.com.epitrack.healthycup.adapters;

import java.util.List;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import br.com.epitrack.healthycup.R;

//adapter para o homeFragment
//mostra a foto e nome do deputado
public class ImageAdapter extends BaseAdapter {

	private Activity activity;
	private List<Integer> imagens;

	public ImageAdapter(Activity activity, List<Integer> imagens) {
		this.activity = activity;
		this.imagens = imagens;
	}

	public void setImagens(List<Integer> imagens) {
		this.imagens = imagens;
	}

	@Override
	public int getCount() {
		return imagens.size();
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
	public View getView(int position, View convertView, ViewGroup parent) {
		// ImageView imageView = (ImageView) convertView;
		ViewHolder holder = null;
		LayoutInflater inflator = activity.getLayoutInflater();
		// if convertView's not recycled, initialize some attributes
		if (convertView == null) {
			holder = new ViewHolder();
			convertView = inflator.inflate(R.layout.item_adapter_sentimento, null);
			holder.imageView = (ImageView) convertView.findViewById(R.id.imgTrofeu);

			convertView.setTag(holder);

		} else {
			holder = (ViewHolder) convertView.getTag();
		}

		holder.imageView.setImageResource(imagens.get(position));
		holder.position = position;

		return convertView;

		// imageView.setImageResource(mThumbIds.get(position));
		// return imageView;
	}

	private class ViewHolder {
		// The position of this row in list
		@SuppressWarnings("unused")
		private int position;

		// The image view for each row
		private ImageView imageView;

	}

	@Override
	public long getItemId(int arg0) {
		// TODO Auto-generated method stub
		return 0;
	}

}
