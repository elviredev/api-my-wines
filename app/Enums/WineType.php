<?php

namespace App\Enums;

enum WineType: string
{
  case ROUGE = 'Rouge';
  case BLANC = 'Blanc';
  case ROSE = 'Rosé';
  case CHAMPAGNE = 'Champagne';
  case SPIRITUEUX = 'Spiritueux';
  case AUTRE = 'Autre';
}
