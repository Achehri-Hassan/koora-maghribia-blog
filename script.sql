drop database if exists  botola_maghribiya;

-- start to create database 
create database botola_maghribiya; 

-- use this botola_maghribiya
use botola_maghribiya;


-- tables users

CREATE TABLE admin(

  id INT AUTO_INCREMENT PRIMARY KEY,

  username VARCHAR(100) NOT NULL,

  email VARCHAR(150) NOT NULL UNIQUE,

  password VARCHAR(255) NOT NULL,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) COMMENT = 'Table The users';


-- table articles

CREATE TABLE articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  image VARCHAR(255),
  category VARCHAR(50),
  user_id int not null, 
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES admin(id) ON DELETE CASCADE

) COMMENT = 'Table the articles';



CREATE TABLE comments (

    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE

) COMMENT = 'Table the comments';



INSERT INTO articles (title,  content, image, category, user_id) VALUES 
(
    'التعادل السلبي يحسم مواجهة اتحاد طنجة والجيش الملكي بملعب طنجة الكبير',
    'حسم التعادل السلبي نتيجة المباراة التي جمعت نادي اتحاد طنجة بضيفه الجيش الملكي، مساء يومه الأحد، على أرضية ملعب طنجة الكبير، ضمن منافسات الجولة 18 من البطولة الاحترافية. وانتهى الشوط الأول على نتيجة البياض رغم المحاولات المتبادلة من الجانبين، حيث سعى كل طرف لافتتاح حصة التسجيل مبكرا وأخذ الأسبقية، غير أن الحذر الدفاعي طغى على معظم فترات هذا الشوط. وخلال الشوط الثاني، ارتفع نسق اللقاء، وشهد محاولات جادة من الطرفين لفك شفرة الدفاع، غير أن تألق حارسي الفريقين ويقظتهما حالا دون بلوغ أي طرف الشباك، لتنتهي المباراة بنتيجة البياض 0-0.',
    'tanger.jpeg',
    'news',
    1
),
(
    'الرجاء الرياضي يهزم المغرب الفاسي بثنائية وينتزع صدارة البطولة مؤقتا',
    'نجح فريق الرجاء الرياضي في العودة بانتصار ثمين من قلب فاس، عقب تغلبه على مضيفه المغرب الرياضي الفاسي بهدفين دون رد، في المواجهة التي جمعتهما مساء يومه الأربعاء على أرضية المركب الرياضي بفاس، لحساب منافسات الجولة التاسعة عشرة من البطولة الاحترافية. وأنهى الفريق الأخضر الفصل الأول من اللقاء متفوقا بهدف نظيف وقعه المهاجم النيجيري ماتياس أوييووسي عند الدقيقة 19.',
    'A1.jpg',
    'news',
    1
),
(
    'فوز مثير للجيش أمام حسنية أكادير',
    'حقق الجيش الملكي فوزا مثيرا على حساب مضيفه حسنية أكادير بثلاثة أهداف لهدفين، في المباراة التي جمعتهما مساء يوم الأحد على أرضية ملعب أدرار، لحساب الجولة 20 من منافسات البطولة الاحترافية. ودخل الفريق العسكري المباراة بقوة، حيث افتتح جلال الدين الخفيف التسجيل مبكرا في الدقيقة السابعة، مانحا التقدم للضيوف منذ البداية.',
    'A2.jpg',
    'news',
    1
),
(
    'التعادل الإيجابي يحسم مواجهة آسفي والفتح',
    'حسم التعادل الإيجابي مواجهة أولمبيك آسفي وضيفه الفتح الرياضي، بهدف لمثله، في المباراة التي جمعتهما مساء اليوم الإثنين، على أرضية ملعب المسيرة بمدينة آسفي، لحساب الجولة 20 من البطولة الاحترافية. وأنهى أولمبيك آسفي الشوط الأول متقدما بهدف نظيف، سجله اللاعب فراجي كرمون في الدقيقة 28 عن طريق ضربة جزاء.',
    'A3.jpeg',
    'news',
    1
),
(
    'بدر بانون يوضح ويعتذر للمسفيويين',
    'Hassan',
    'خرج بدر بانون، لاعب الرجاء الرياضي لكرة القدم، بتوضيح رسمي عقب الجدل الذي رافق الفيديو المتداول بعد نهاية الديربي البيضاوي أمام الوداد الرياضي، والذي ظهر فيه في نقاش مع عبد الغفور لاميرات. وأكد بانون، عبر ستوري بحسابه الرسمي على موقع إنستغرام، أن الحديث الذي جمعه بلاميرات كان مرتبطا بسوء تفاهم سابق.',
    'A4.jpg',
    'news',
    1
),
(
    'البطولة الاحترافية تشهد منافسة قوية هذا الموسم',
    'تواصل البطولة الاحترافية المغربية جذب اهتمام الجماهير هذا الموسم بفضل المباريات القوية والمنافسة الكبيرة بين الأندية على صدارة الترتيب. وشهدت الجولات الأخيرة ارتفاع مستوى الأداء داخل أرضية الملعب، حيث أصبحت الفرق تعتمد على أساليب لعب حديثة وتنظيم تكتيكي واضح.',
    'botola-maroc.jpg',
    'news',
    1
);


INSERT INTO admin (username, email, password)
VALUES (
'admin',
'admin@gmail.com',
'1234'
);

select * from articles;

select * from admin;

select * from comments;

