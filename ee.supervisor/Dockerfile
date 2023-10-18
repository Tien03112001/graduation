FROM python:3.7

ENV PIP_ROOT_USER_ACTION=ignore
RUN pip install --upgrade pip

WORKDIR /usr/src/app

COPY . .

RUN pip install -r requirements.txt

CMD ["python", "./app.py"]