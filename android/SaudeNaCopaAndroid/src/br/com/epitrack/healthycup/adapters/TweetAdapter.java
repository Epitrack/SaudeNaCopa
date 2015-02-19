package br.com.epitrack.healthycup.adapters;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import android.app.Activity;
import android.text.util.Linkify;
import android.text.util.Linkify.TransformFilter;
import android.util.Patterns;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Tweet;

public class TweetAdapter extends BaseAdapter {

	private Activity activity;
	private List<Tweet> tweeters;

	public TweetAdapter(Activity activity2, List<Tweet> tweeters) {
		//
		this.tweeters = tweeters;
		this.activity = activity2;
	}

	@Override
	public int getCount() {
		return tweeters.size();
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
			convertView = inflator.inflate(R.layout.item_twitter, null);
			holder.nome = (TextView) convertView.findViewById(R.id.txtTwitterNomeApresentacao);
			holder.nick = (TextView) convertView.findViewById(R.id.txtTwitterNick);
			holder.mensagem = (TextView) convertView.findViewById(R.id.txtTwitterMsg);
			holder.tempo = (TextView) convertView.findViewById(R.id.txtTwitterTempo);

			convertView.setTag(holder);

		} else {
			holder = (ViewHolder) convertView.getTag();
		}

		holder.nome.setText(tweeters.get(position).getNick());
		holder.nick.setText(tweeters.get(position).getNome());
		holder.mensagem.setText(tweeters.get(position).getTexto()+"\n");

		TransformFilter filter = new TransformFilter() {
			public final String transformUrl(final Matcher match, String url) {
				return match.group();
			}
		};

		Pattern mentionPattern = Pattern.compile("@([A-Za-z0-9_-]+)");
		String mentionScheme = "http://www.twitter.com/";
		Linkify.addLinks(holder.mensagem, mentionPattern, mentionScheme, null, filter);
		Linkify.addLinks(holder.nick, mentionPattern, mentionScheme, null, filter);

		Pattern hashtagPattern = Pattern.compile("#([A-Za-z0-9_-]+)");
		String hashtagScheme = "http://www.twitter.com/search/";
		Linkify.addLinks(holder.mensagem, hashtagPattern, hashtagScheme, null, filter);

		Pattern urlPattern = Patterns.WEB_URL;
		Linkify.addLinks(holder.mensagem, urlPattern, null, null, filter);

		try {
			holder.tempo.setText((getTwitterDate(convertData(tweeters.get(position).getTempo()))));
		} catch (ParseException e) {
			e.printStackTrace();
		}

		holder.position = position;

		return convertView;

	}

	private Date convertData(String tempo) throws ParseException {		
		final String TWITTER = "EEE MMM dd HH:mm:ss ZZZZZ yyyy";
		SimpleDateFormat sf = new SimpleDateFormat(TWITTER, Locale.ENGLISH);
		sf.setLenient(true);
		return sf.parse(tempo);
	}

	public String getTwitterDate(Date date) throws ParseException {
		DateFormat df = new SimpleDateFormat("EEE MMM dd HH:mm:ss ZZZZZ yyyy");
		String dataAtual = df.format(new Date().getTime());
		
		long milliseconds = convertData(dataAtual).getTime()-date.getTime();
		int minutes = (int) ((milliseconds / (1000 * 60)) % 60);
		int hours = (int) ((milliseconds / (1000 * 60 * 60)) % 24);

		if (hours > 0) {
			if (hours == 1)
				return "1 hr";
			else if (hours < 24)
				return String.valueOf(hours) + " hrs";
			else {
				int days = (int) Math.ceil(hours % 24);
				if (days == 1)
					return "1 day";
				else
					return String.valueOf(days) + " days";
			}
		} else {
			if (minutes == 0)
				return "less than 1 min";
			else if (minutes == 1)
				return "1 min";
			else
				return String.valueOf(minutes) + " min";
		}
	}

	private class ViewHolder {
		// The position of this row in list
		@SuppressWarnings("unused")
		private int position;

		// The image view for each row
		private TextView nome;
		private TextView nick;
		private TextView mensagem;
		private TextView tempo;

	}

	@Override
	public long getItemId(int arg0) {
		// Auto-generated method stub
		return 0;
	}

}
