package br.com.epitrack.healthycup.adapters;

import java.util.HashMap;
import java.util.List;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.LinearLayout;
import android.widget.TextView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Jogo;

public class CalendarioJogosAdapter extends BaseExpandableListAdapter {

	private Context _context;
	private List<String> _listDataHeader; // header titles
	// child data in format of header title, child title
	private HashMap<String, List<Jogo>> _listDataChild;

	public CalendarioJogosAdapter(Context context, List<String> listDataHeader, HashMap<String, List<Jogo>> listChildData) {
		this._context = context;
		this._listDataHeader = listDataHeader;
		this._listDataChild = listChildData;
	}

	@Override
	public Object getChild(int groupPosition, int childPosititon) {
		return this._listDataChild.get(this._listDataHeader.get(groupPosition)).get(childPosititon);
	}

	@Override
	public long getChildId(int groupPosition, int childPosition) {
		return childPosition;
	}

	@Override
	public View getChildView(int groupPosition, final int childPosition, boolean isLastChild, View convertView, ViewGroup parent) {

		final Jogo jogo = (Jogo) getChild(groupPosition, childPosition);

		if (convertView == null) {
			LayoutInflater infalInflater = (LayoutInflater) this._context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
			convertView = infalInflater.inflate(R.layout.list_calendario_jogos_item, null);
		}
		
		//grupo
		TextView lblGrupo = (TextView) convertView.findViewById(R.id.lblGrupo);
		if(jogo.getGrupo().length() > 0){
			lblGrupo.setVisibility(View.VISIBLE);
			lblGrupo.setText(jogo.getGrupo());
		}else{
			lblGrupo.setVisibility(View.GONE);
		}
		
		//rodada
		LinearLayout lnRodada = (LinearLayout)convertView.findViewById(R.id.lnRodada);		
		TextView lblRodada = (TextView) convertView.findViewById(R.id.lblRodada);
		if(jogo.getRodada().length() > 0){
			lnRodada.setVisibility(View.VISIBLE);
			lblRodada.setText(jogo.getRodada());
		}else{
			lnRodada.setVisibility(View.GONE);
		}
		
		//time
		TextView lblTimes = (TextView) convertView.findViewById(R.id.lblTimes);
		lblTimes.setText(jogo.getTimes());
		//horario
		TextView lblHorario = (TextView) convertView.findViewById(R.id.lblHorario);
		lblHorario.setText(jogo.getHorario()+" - "+jogo.getArena());

		return convertView;
	}

	@Override
	public int getChildrenCount(int groupPosition) {
		  return this._listDataChild.get(this._listDataHeader.get(groupPosition))
	                .size();
	}

	@Override
	public Object getGroup(int groupPosition) {
		return this._listDataHeader.get(groupPosition);
	}

	@Override
	public int getGroupCount() {
		return this._listDataHeader.size();
	}

	@Override
	public long getGroupId(int groupPosition) {
		return groupPosition;
	}

	@Override
	public View getGroupView(int groupPosition, boolean isExpanded, View convertView, ViewGroup parent) {
		String headerTitle = (String) getGroup(groupPosition);
		if (convertView == null) {
			LayoutInflater infalInflater = (LayoutInflater) this._context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
			convertView = infalInflater.inflate(R.layout.list_calendario_jogo_group, null);
		}

		TextView lblListHeader = (TextView) convertView.findViewById(R.id.lblListHeader);
		lblListHeader.setText(headerTitle.toUpperCase());

		return convertView;
	}

	@Override
	public boolean hasStableIds() {
		return false;
	}

	@Override
	public boolean isChildSelectable(int groupPosition, int childPosition) {
		return true;
	}
}
