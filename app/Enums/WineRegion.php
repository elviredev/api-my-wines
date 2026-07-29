<?php

namespace App\Enums;

enum WineRegion: string
{
  case ALSACE = 'Alsace';
  case BEAUJOLAIS = 'Beaujolais';
  case BORDEAUX = 'Bordeaux';
  case BOURGOGNE = 'Bourgogne';
  case CHAMPAGNE = 'Champagne';
  case CORSE = 'Corse';
  case JURA = 'Jura';
  case LANGUEDOC = 'Languedoc';
  case LYONNAIS = 'Lyonnais';
  case LORRAINE = 'Lorraine';
  case PROVENCE = 'Provence';
  case ROUSSILLON = 'Roussillon';
  case SAVOIE = 'Savoie';
  case SUD_OUEST = 'Sud-Ouest';
  case VAL_DE_LOIRE = 'Val de Loire';
  case VALLEE_DU_RHONE = 'Vallée du Rhone';
}
