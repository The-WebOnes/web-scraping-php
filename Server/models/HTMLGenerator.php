<?php 
class HTMLGenerator
{
    public function generate_Table($Array)
    {
        
        $template = '<div class="table-responsive">';
        $template .= '<table class="table table-bordered">';
        $template .= '<thead>
        <tr>
          <th scope="col" >Link</th>
          <th scope="col" >Fragmento</th>
          <th scope="col" >Puntaje</th>
        </tr>
      </thead>
      <tbody>';
        foreach ($Array as $item) {
            $template .= '<tr>';
            $template.= '<td>'.$item['link'] .'</td>';
            $template.= '<td>'.$item['snipped'] .'</td>';
            $template.= '<td>'.$item['score'] .'</td>';
            $template .= '</tr>';
         }
         $template .= '
         </tbody>
         </table>
         </div>
         ';
        return $template;
    }
}


?>