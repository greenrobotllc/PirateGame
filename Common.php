<?php

class CommonConstants {
    public static $LIB_NAME = "MyOpenSpace PHP";
    public static $LIB_VERSION = "v0.1.20080315";
    
    public static $URL_OPENSOCIAL_ROOT_API = "http://api.msappspace.com/opensocial";
    public static $URL_ROOT_API = "http://api.myspace.com";
    public static $URL_SEPERATOR = "/";
    
    public static $X_HTTP_METHOD_OVERRIDE_HEADER = "X-HTTP-Method-Override";
}

class XmlNameSpaceList {
    public static $NS_API_V1 = "api-v1.myspace.com";
    public static $NS_XSI = "http://www.w3.org/2001/XMLSchema-instance";
    public static $NS_XSD = "http://www.w3.org/2001/XMLSchema";
}

class ApiVersionType {
    public static $VERSION1 = "v1";
}

class ResponseType {
    public static $XML = "XML";
    public static $JSON = "JSON";
    public static $AMF = "AMF";
}

class SurfaceType {
    public static $CANVAS = "canvas";
    public static $HOME = "home";
    public static $PROFILE_LEFT = "profile.left";
    public static $PROFILE_RIGHT = "profile.right";
}

class HttpMethodType {
    public static $GET = "GET";
    public static $POST = "POST";
    public static $PUT = "PUT";
    public static $DELETE = "DELETE";
}

class MySpaceMoodType {
    public static $NONE = 0;
    public static $ACCOMPLISHED = 90;
    public static $ADORED = 135;
    public static $ADVENTUROUS = 136;
    public static $AGGRAVATED = 1;
    public static $AMOROUS = 137;
    public static $AMUSED = 44;
    public static $ANGRY = 2;
    public static $ANGSTY = 138;
    public static $ANIMATED = 139;
    public static $ANNOYED = 3;
    public static $ANXIOUS = 4;
    public static $APATHETIC = 114;
    public static $ARGUMENTATIVE = 140;
    public static $AROUSED = 141;
    public static $ARTISTIC = 108;
    public static $ASHAMED = 142;
    public static $AWAKE = 87;
    public static $BETRAYED = 143;
    public static $BITCHY = 110;
    public static $BLAH = 92;
    public static $BLANK = 113;
    public static $BLESSED = 144;
    public static $BLISSFUL = 145;
    public static $BLUSTERY = 146;
    public static $BORED = 5;
    public static $BOUNCY = 59;
    public static $BREEZY = 147;
    public static $BULLIED = 148;
    public static $BUMMED = 149;
    public static $BUSY = 91;
    public static $CALM = 68;
    public static $CANTANKEROUS = 150;
    public static $CATALYZED = 151;
    public static $CHEERFUL = 125;
    public static $CHILL = 152;
    public static $CHIPPER = 99;
    public static $COLD = 84;
    public static $COMPLACENT = 63;
    public static $CONFIDENT = 153;
    public static $CONFUSED = 6;
    public static $CONTEMPLATIVE = 101;
    public static $CONTENT = 64;
    public static $COOKY_WACKY = 154;
    public static $CRANKY = 8;
    public static $CRAPPY = 7;
    public static $CRAZY = 106;
    public static $CREATIVE = 107;
    public static $CRUNK = 155;
    public static $CRUSHED = 129;
    public static $CULTURED = 156;
    public static $CURIOUS = 56;
    public static $CYNICAL = 104;
    public static $DEPRESSED = 9;
    public static $DETERMINED = 45;
    public static $DEVIOUS = 130;
    public static $DIRTY = 119;
    public static $DISAPPOINTED = 55;
    public static $DISCONTENT = 10;
    public static $DISGUSTED = 157;
    public static $DISTRACTABLE = 158;
    public static $DISTRAUGHT = 159;
    public static $DISTRESSED = 127;
    public static $DITZY = 35;
    public static $DORKY = 115;
    public static $DRAINED = 40;
    public static $DRUNK = 34;
    public static $ECCENTRIC = 160;
    public static $ECSTATIC = 98;
    public static $ELECTRIC = 161;
    public static $EMBARRASSED = 79;
    public static $ENERGETIC = 11;
    public static $ENLIGHTENED = 162;
    public static $ENRAGED = 12;
    public static $ENTHRALLED = 13;
    public static $ENVIOUS = 80;
    public static $EVIL = 163;
    public static $EXANIMATE = 78;
    public static $EXCITED = 41;
    public static $EXHAUSTED = 14;
    public static $EXOTIC = 164;
    public static $FABULOUS = 165;
    public static $FASCINATED = 166;
    public static $FERMENTED = 167;
    public static $FLIRTY = 67;
    public static $FOCUSED = 168;
    public static $FORGOTTEN = 169;
    public static $FRISKY = 170;
    public static $FROGGY = 171;
    public static $FRUSTRATED = 47;
    public static $FULL = 93;
    public static $GALLANT = 172;
    public static $GEEKY = 103;
    public static $GIDDY = 120;
    public static $GIGGLY = 72;
    public static $GLOOMY = 38;
    public static $GOOD = 126;
    public static $GRATEFUL = 132;
    public static $GROGGY = 51;
    public static $GRUMPY = 95;
    public static $GUILTY = 111;
    public static $HANDSOME = 173;
    public static $HAPPY = 15;
    public static $HIGH = 16;
    public static $HOPEFUL = 43;
    public static $HORNY = 17;
    public static $HOT = 83;
    public static $HUNGOVER = 174;
    public static $HUNGRY = 18;
    public static $HYPER = 52;
    public static $IMAGINATIVE = 175;
    public static $IMPATIENT = 176;
    public static $IMPERVIOUS = 177;
    public static $IMPLACABLE = 178;
    public static $IMPRESSED = 116;
    public static $INDESCRIBABLE = 48;
    public static $INDIFFERENT = 65;
    public static $INDIGNANT = 179;
    public static $INFURIATED = 19;
    public static $INQUISITIVE = 180;
    public static $INSPIRED = 181;
    public static $INSUBORDINATE = 182;
    public static $INTENSE = 183;
    public static $INTIMIDATED = 128;
    public static $IRATE = 20;
    public static $IRRITATED = 112;
    public static $JEALOUS = 133;
    public static $JEDI = 184;
    public static $JOLLY = 185;
    public static $JUBILANT = 21;
    public static $KNIGHTED = 186;
    public static $LAZY = 33;
    public static $LETHARGIC = 75;
    public static $LISTLESS = 76;
    public static $LONELY = 22;
    public static $LOVED = 86;
    public static $LUMINOUS = 187;
    public static $MAD = 188;
    public static $MELANCHOLY = 39;
    public static $MELLOW = 57;
    public static $MISCHIEVOUS = 36;
    public static $MISERABLE = 189;
    public static $MOODY = 23;
    public static $MOROSE = 37;
    public static $NAUGHTY = 117;
    public static $NAUSEATED = 97;
    public static $NEGLECTED = 190;
    public static $NERDY = 102;
    public static $NERVOUS = 134;
    public static $NINJA = 191;
    public static $NOSTALGIC = 60;
    public static $NUMB = 124;
    public static $OBSEQUIOUS = 192;
    public static $OKAY = 61;
    public static $OPTIMISTIC = 70;
    public static $OVERSTIMULATED = 193;
    public static $PEACEFUL = 58;
    public static $PEEVED = 194;
    public static $PENSIVE = 73;
    public static $PESSIMISTIC = 71;
    public static $PIRATE = 195;
    public static $PISSED_OFF = 24;
    public static $PISSY = 196;
    public static $PLAYED = 197;
    public static $PLEASED = 109;
    public static $PRETTY = 198;
    public static $PRODUCTIVE = 89;
    public static $PUGNACIOUS = 199;
    public static $PURE = 200;
    public static $QUIET = 201;
    public static $QUIXOTIC = 105;
    public static $REBELLIOUS = 202;
    public static $RECUMBENT = 77;
    public static $REFRESHED = 69;
    public static $REJECTED = 123;
    public static $REJUVENATED = 62;
    public static $RELAXED = 53;
    public static $RELIEVED = 42;
    public static $RESTLESS = 54;
    public static $ROCKIN = 203;
    public static $ROMANTIC = 204;
    public static $RUSHED = 100;
    public static $SAD = 25;
    public static $SASSY = 205;
    public static $SATISFIED = 26;
    public static $SAVAGE = 118;
    public static $SCARED = 46;
    public static $SELECTIVE = 206;
    public static $SHOCKED = 122;
    public static $SICK = 82;
    public static $SILLY = 66;
    public static $SLEEPY = 49;
    public static $SMART = 207;
    public static $SMITTEN = 208;
    public static $SNEAKY = 209;
    public static $SNEEZY = 210;
    public static $SORE = 27;
    public static $STALKED = 211;
    public static $STOKED = 212;
    public static $STRESSED = 28;
    public static $STRONG = 213;
    public static $SURPRISED = 121;
    public static $SWEATY = 214;
    public static $SYMPATHETIC = 81;
    public static $TALKATIVE = 215;
    public static $TESTED = 216;
    public static $THANKFUL = 131;
    public static $THIRSTY = 29;
    public static $THOUGHTFUL = 30;
    public static $TIRED = 31;
    public static $TOUCHED = 32;
    public static $TRIUMPHANT = 217;
    public static $UNCOMFORTABLE = 74;
    public static $UNDERSTIMULATED = 218;
    public static $USED = 219;
    public static $VALIDATED = 220;
    public static $VEHEMENT = 221;
    public static $VEXED = 222;
    public static $VIBRANT = 223;
    public static $VIRGINAL = 224;
    public static $VITAL = 225;
    public static $VOLUMINOUS = 226;
    public static $WANTED = 227;
    public static $WARM = 228;
    public static $WEIRD = 96;
    public static $WORKING = 88;
    public static $WORRIED = 85;
}


// OpenSocial Specific

class OpenSocialQueryStringList {
    public static $QS_OPEN_SOCIAL_TOKEN = "opensocial_token";
    public static $QS_OPEN_SOCIAL_VIEW = "opensocial_mode";
    public static $QS_DETAIL_TYPE = "detailtype";    
}

class ContextType {
    public static $VIEWER = "VIEWER";
    public static $OWNER = "OWNER";
}

class DetailType {
    public static $DETAIL = "DETAIL";
    public static $BASIC = "BASIC";
    public static $FULL = "FULL";
}


?>